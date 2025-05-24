<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Firestore;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Factory::class, function ($app) {
            $credentialsPath = config('firebase.credentials.file');
            
            if ($credentialsPath && !str_starts_with($credentialsPath, '/')) {
                $credentialsPath = base_path($credentialsPath);
            }
            
            if (!$credentialsPath || !file_exists($credentialsPath)) {
                throw new \Exception('Firebase credentials file not found');
            }
            
            return (new Factory)->withServiceAccount($credentialsPath);
        });
        
        // Bind the Kreait Firestore service
        $this->app->singleton(Firestore::class, function ($app) {
            return $app->make(Factory::class)->createFirestore();
        });
    }

    public function boot(): void
    {
        //
    }
}