<?php

namespace App\Jobs;

use Excel;
use Storage;
use App\Import;
use App\Product;
use App\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessImportedProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $toProcess = Import::where('processed', 0)->get();

        foreach($toProcess as $import) {

            if(\Storage::exists('imports/' . $import->file)) {
                $document = Excel::load("/storage/app/imports/" . $import->file)->get();

                foreach($document as $row) {
                    
                    try {
                        \DB::beginTransaction();

                        $category = Category::where('name', $row->category)->first();

                        if(!$category) {
                            $category = Category::create(['name' => $row->category]);
                        }

                        $product = Product::create([
                            'name' => $row->name,
                            'category_id' => $category->id,
                            'price' => $row->price
                        ]);

                        \Log::info('New product imported: ' . $product->id);

                        $import->update(['processed' => 1]);
                        $import->save();

                        \DB::commit();

                    } catch(\Exception $e) {
                        \DB::rollback();

                        \Mail::send([], [], function($message) use ($import, $e) {
                            $message->to($import->user->email)
                                ->subject('Failed to import')
                                ->setBody('Error: ' . $e->getMessage());
                        });
                    }

                }

                \Mail::send([], [], function($message) use ($import) {
                    $message->to($import->user->email)
                        ->subject('Imported products successfully')
                        ->setBody('Your products are imported !');
                });
            }

        }
    }
}
