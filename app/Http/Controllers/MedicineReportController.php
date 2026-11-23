<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ObatAnak;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MedicineReportController extends Controller
{
    private const REPORT_TYPES = [
        'data' => 'Laporan Data Obat',
        'stok' => 'Laporan Stok Obat',
        'hampir_habis' => 'Laporan Obat Hampir Habis',
        'kedaluwarsa' => 'Laporan Obat Kedaluwarsa',
        'kategori' => 'Laporan Berdasarkan Kategori',
    ];

    public function index(Request $request): View
    {
        $filters = $this->validatedFilters($request);
        $type = $filters['jenis_laporan'];
        $categories = Category::query()->orderBy('nama')->get();
        $categoryRows = collect();
        $medicines = collect();

        if ($type === 'kategori') {
            $categoryRows = Category::query()
                ->with(['obatAnaks' => fn ($query) => $this->applyFilters($query, $filters)])
                ->withCount(['obatAnaks' => fn (Builder $query) => $this->applyFilters($query, $filters)])
                ->orderBy('nama')
                ->get();
        } else {
            $medicines = $this->applyFilters(ObatAnak::query()->with('category'), $filters)
                ->orderBy('nama_obat')
                ->get();
        }

        return view('obat-anak.laporan', [
            'title' => self::REPORT_TYPES[$type],
            'filters' => $filters,
            'categories' => $categories,
            'categoryRows' => $categoryRows,
            'obatAnak' => $medicines,
            'totalObat' => $medicines->count(),
            'stokHabis' => $medicines->where('stok', 0)->count(),
            'kategoriCount' => $categories->count(),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $this->validatedFilters($request);
        $type = $filters['jenis_laporan'];
        $filename = 'laporan-obat-'.$type.'-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($filters, $type): void {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");

            if ($type === 'kategori') {
                fputcsv($output, ['Kategori', 'Jumlah jenis obat', 'Daftar nama obat']);
                $categories = Category::query()
                    ->with(['obatAnaks' => fn ($query) => $this->applyFilters($query, $filters)])
                    ->withCount(['obatAnaks' => fn (Builder $query) => $this->applyFilters($query, $filters)])
                    ->orderBy('nama')
                    ->get();

                foreach ($categories as $category) {
                    fputcsv($output, $this->safeCsvRow([
                        $category->nama,
                        $category->obat_anaks_count,
                        $category->obatAnaks->pluck('nama_obat')->implode(', '),
                    ]));
                }
            } else {
                fputcsv($output, $this->reportHeaders($type));
                $medicines = $this->applyFilters(ObatAnak::query()->with('category'), $filters)
                    ->orderBy('nama_obat')
                    ->cursor();

                foreach ($medicines as $medicine) {
                    $stockStatus = $medicine->stok === 0
                        ? 'Habis'
                        : ($medicine->stok <= $medicine->stok_minimum ? 'Menipis' : 'Tersedia');
                    $expiryStatus = $medicine->tanggal_kedaluwarsa?->isBefore(today())
                        ? 'Kedaluwarsa'
                        : ($medicine->tanggal_kedaluwarsa?->lte(today()->addDays(30)) ? 'Segera kedaluwarsa' : 'Aman');

                    fputcsv($output, $this->safeCsvRow(match ($type) {
                        'stok' => [$medicine->nama_obat, $medicine->stok, $medicine->satuan, $stockStatus],
                        'hampir_habis' => [$medicine->nama_obat, $medicine->stok, $medicine->stok_minimum, $stockStatus],
                        'kedaluwarsa' => [$medicine->nama_obat, $medicine->kategori, $medicine->stok, $medicine->tanggal_kedaluwarsa?->format('Y-m-d'), $expiryStatus],
                        default => [$medicine->nama_obat, $medicine->kategori, $medicine->bentuk, $medicine->dosis, $medicine->stok, $medicine->satuan, $medicine->tanggal_kedaluwarsa?->format('Y-m-d'), $medicine->keterangan],
                    }));
                }
            }

            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * @return array{jenis_laporan: string, category_id: ?int, tanggal_mulai: ?string, tanggal_selesai: ?string, status_stok: ?string}
     */
    private function validatedFilters(Request $request): array
    {
        $validated = $request->validate([
            'jenis_laporan' => ['nullable', Rule::in(array_keys(self::REPORT_TYPES))],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'status_stok' => ['nullable', Rule::in(['habis', 'menipis', 'tersedia'])],
        ]);

        return array_merge([
            'jenis_laporan' => 'data',
            'category_id' => null,
            'tanggal_mulai' => null,
            'tanggal_selesai' => null,
            'status_stok' => null,
        ], $validated);
    }

    /**
     * @param  array{category_id: ?int, tanggal_mulai: ?string, tanggal_selesai: ?string, status_stok: ?string}  $filters
     */
    private function applyFilters(Builder $query, array $filters): Builder
    {
        if ($filters['category_id'] !== null) {
            $query->where('category_id', $filters['category_id']);
        }

        if ($filters['tanggal_mulai'] !== null) {
            $query->whereDate('tanggal_kedaluwarsa', '>=', $filters['tanggal_mulai']);
        }

        if ($filters['tanggal_selesai'] !== null) {
            $query->whereDate('tanggal_kedaluwarsa', '<=', $filters['tanggal_selesai']);
        }

        if ($filters['jenis_laporan'] === 'hampir_habis') {
            $query->whereColumn('stok', '<=', 'stok_minimum');
        }

        if ($filters['jenis_laporan'] === 'kedaluwarsa') {
            $query->whereNotNull('tanggal_kedaluwarsa')
                ->whereDate('tanggal_kedaluwarsa', '<', today());
        }

        return match ($filters['status_stok']) {
            'habis' => $query->where('stok', 0),
            'menipis' => $query->where('stok', '>', 0)->whereColumn('stok', '<=', 'stok_minimum'),
            'tersedia' => $query->whereColumn('stok', '>', 'stok_minimum'),
            default => $query,
        };
    }

    /**
     * @return array<int, string>
     */
    private function reportHeaders(string $type): array
    {
        return match ($type) {
            'stok' => ['Nama Obat', 'Stok tersedia', 'Satuan', 'Status stok'],
            'hampir_habis' => ['Nama Obat', 'Jumlah stok', 'Batas minimum stok', 'Status persediaan'],
            'kedaluwarsa' => ['Nama Obat', 'Kategori', 'Jumlah stok', 'Tanggal kedaluwarsa', 'Status obat'],
            default => ['Nama Obat', 'Kategori', 'Bentuk obat', 'Dosis/Konsentrasi', 'Jumlah stok', 'Satuan', 'Tanggal kedaluwarsa', 'Keterangan'],
        };
    }

    /**
     * @param  array<int, string|int|null>  $row
     * @return array<int, string|int|null>
     */
    private function safeCsvRow(array $row): array
    {
        return array_map(static function (string|int|null $value): string|int|null {
            if (is_string($value) && preg_match('/^[\s\x00-\x20]*[=+\-@]/u', $value) === 1) {
                return "'".$value;
            }

            return $value;
        }, $row);
    }
}
