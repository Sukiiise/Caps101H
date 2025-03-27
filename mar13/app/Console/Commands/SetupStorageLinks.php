<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupStorageLinks extends Command
{
    protected $signature = 'storage:setup';
    protected $description = 'Create all necessary storage links and directories';

    public function handle()
    {
        // Create necessary directories
        $directories = [
            storage_path('app/public/images'),
            storage_path('app/public/images/school-logos'),
            storage_path('app/public/images/profile-photos'),
            storage_path('app/public/images/temp'),
        ];

        // Create directories with proper permissions
        foreach ($directories as $directory) {
            if (!File::exists($directory)) {
                try {
                    File::makeDirectory($directory, 0755, true);
                    $this->info("✓ Created directory: {$directory}");
                } catch (\Exception $e) {
                    $this->error("Failed to create directory: {$directory}");
                }
            } else {
                $this->line("Directory already exists: {$directory}");
            }
        }

        // Handle storage link
        try {
            $storagePath = public_path('storage');
            if (is_link($storagePath)) {
                unlink($storagePath);
                $this->info('Removed existing storage link');
            }
            
            $this->call('storage:link');
            $this->info('✓ Created storage link');
        } catch (\Exception $e) {
            $this->error('Failed to create storage link: ' . $e->getMessage());
        }

        // Handle images link
        try {
            $imagesPath = public_path('images');
            
            // Remove existing directory or symlink
            if (File::exists($imagesPath)) {
                if (is_link($imagesPath)) {
                    unlink($imagesPath);
                } else {
                    File::deleteDirectory($imagesPath);
                }
                $this->info('Removed existing images directory/link');
            }

            // Create the symbolic link
            File::link(
                storage_path('app/public/images'),
                public_path('images')
            );
            $this->info('✓ Created images link');
        } catch (\Exception $e) {
            $this->error('Failed to create images link: ' . $e->getMessage());
        }

        // Verify setup
        $this->newLine();
        $this->info('Verifying setup...');
        
        $checks = [
            'storage_link' => [
                'exists' => File::exists(public_path('storage')),
                'is_link' => is_link(public_path('storage'))
            ],
            'images_link' => [
                'exists' => File::exists(public_path('images')),
                'is_link' => is_link(public_path('images'))
            ],
            'directories' => [
                'school_logos' => File::exists(storage_path('app/public/images/school-logos')),
                'profile_photos' => File::exists(storage_path('app/public/images/profile-photos')),
                'temp' => File::exists(storage_path('app/public/images/temp'))
            ]
        ];

        $allPassed = true;

        foreach ($checks['storage_link'] as $key => $value) {
            if (!$value) {
                $this->error("✗ Storage link check failed: {$key}");
                $allPassed = false;
            }
        }

        foreach ($checks['images_link'] as $key => $value) {
            if (!$value) {
                $this->error("✗ Images link check failed: {$key}");
                $allPassed = false;
            }
        }

        foreach ($checks['directories'] as $key => $value) {
            if (!$value) {
                $this->error("✗ Directory check failed: {$key}");
                $allPassed = false;
            }
        }

        if ($allPassed) {
            $this->newLine();
            $this->info('✓ Storage setup completed successfully!');
            $this->info('You can now access your files through:');
            $this->line('  • /storage');
            $this->line('  • /images');
            $this->line('  • /images/school-logos');
            $this->line('  • /images/profile-photos');
            $this->line('  • /images/temp');
        } else {
            $this->newLine();
            $this->error('Setup verification failed. Please check the errors above.');
        }
    }
}
