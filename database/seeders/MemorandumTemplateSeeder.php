<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MemorandumTemplate;
use Illuminate\Support\Facades\DB;

class MemorandumTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Deactivate all existing templates first
        MemorandumTemplate::query()->update(['is_active' => false]);

        // Create default DENR Memorandum Template based on the provided document
        MemorandumTemplate::create([
            'name' => 'DENR PENRO Occidental Mindoro - Official Memorandum',
            'version' => '1.0',
            'description' => 'Official DENR memorandum template for PENRO Occidental Mindoro',
            'layout_config' => [
                'margin_top' => 1,      // inches
                'margin_right' => 1,
                'margin_bottom' => 1,
                'margin_left' => 1,
                'font_family' => 'Times New Roman',
                'font_size' => 12,      // points
                'line_height' => 1.5,
                'header_font_size' => 12,
                'title_font_size' => 14,
                'paragraph_spacing' => 12, // points
                'first_line_indent' => 0.5, // inches (36 points)
            ],
            'header_line_1' => 'Republic of the Philippines',
            'header_line_2' => 'Department of Environment and Natural Resources',
            'header_line_3' => 'Provincial Environment and Natural Resources Office',
            'header_line_4' => 'Occidental Mindoro',
            'footer_template' => null, // Can be customized later
            'is_active' => true,
            'created_by' => 1, // Assuming admin user ID is 1
        ]);

        // You can add more template versions here
        // Example: Alternative template with different formatting
        MemorandumTemplate::create([
            'name' => 'DENR PENRO - Compact Format',
            'version' => '1.1',
            'description' => 'Compact version with smaller margins',
            'layout_config' => [
                'margin_top' => 0.75,
                'margin_right' => 0.75,
                'margin_bottom' => 0.75,
                'margin_left' => 0.75,
                'font_family' => 'Arial',
                'font_size' => 11,
                'line_height' => 1.3,
                'header_font_size' => 11,
                'title_font_size' => 13,
                'paragraph_spacing' => 10,
                'first_line_indent' => 0.4,
            ],
            'header_line_1' => 'Republic of the Philippines',
            'header_line_2' => 'Department of Environment and Natural Resources',
            'header_line_3' => 'Provincial Environment and Natural Resources Office',
            'header_line_4' => 'Occidental Mindoro',
            'footer_template' => null,
            'is_active' => false, // Not active by default
            'created_by' => 1,
        ]);

        $this->command->info('Memorandum templates seeded successfully!');
    }
}
