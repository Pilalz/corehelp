<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;
use App\Models\Article;

class ExtractPdfText implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public $article;

    /**
     * Create a new job instance.
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->article->content === null && $this->article->file_path) {
            try {
                $fullPath = Storage::disk('public')->path($this->article->file_path);
                
                // Proses berat (Parsing) terjadi DISINI, di background
                $parser = new Parser();
                $pdf = $parser->parseFile($fullPath);
                $text = $pdf->getText();
                
                // Update database diem-diem
                $this->article->updateQuietly([ // updateQuietly biar gak memicu event saving lagi (looping)
                    'content' => nl2br(e($text))
                ]);

            } catch (\Exception $e) {
                // Log error jika gagal
                \Log::error("Gagal ekstrak PDF ID {$this->article->id}: " . $e->getMessage());
            }
        }
    }
}
