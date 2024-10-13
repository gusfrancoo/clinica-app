    <?php

    use App\Http\Controllers\HomeController;
    use Illuminate\Foundation\Application;
    use Illuminate\Support\Facades\Route;
    use Inertia\Inertia;





    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {


        Route::get('/', [HomeController::class, 'index'])->name('/');
       


        Route::prefix('/home')->group(function (){
            
            Route::post('/upload', [HomeController::class, 'upload']);
            Route::post('/get-files', [HomeController::class, 'getFiles']);
        });

    });

