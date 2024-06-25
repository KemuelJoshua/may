<?php

namespace App\Services;

use App\Models\Carousel;
use App\Models\Service;
use App\Models\Solution;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

use function PHPUnit\Framework\returnSelf;

class ServicesService 
{

    protected $MODEL;

    public function setModel(string $model): void
    {
        $this->MODEL = $model;
    }

    protected function getModel(): string
    {
        switch ($this->MODEL) {
            case 'solutions':
                return Solution::class;
            case 'services':
                return Service::class;
            case 'carousels':
            return Carousel::class;
            default:
                throw new InvalidArgumentException('Invalid partner type');
        }
    }

    public function getService($id)
    {
        $model = $this->getModel();
        return $model::findOrFail($id);
    }

    public function addService($payload)
    {
        $model = $this->getModel();
        $service = $model::create([
            'thumbnail' => 'storage/' . $payload['thumbnail'],
            'title' => $payload['title'],
            'description' => $payload['description'],
        ]);

        return $service;
    }

    public function updateService($payload, $id)
    {
        $service = $this->getService($id);

        if (isset($payload['thumbnail']) && $payload['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
            if ($service->thumbnail && $service->thumbnail != 'img/default.jpg') {
                Storage::disk('public')->delete($service->thumbnail);
            }
            $path = $payload['thumbnail']->store('thumbnails', 'public');
            $payload['thumbnail'] = $path;

            $service->update([
                'thumbnail' => 'storage/' . $payload['thumbnail'],
                'title' => $payload['title'],
                'description' => $payload['description'],
            ]);
        } else {
            $service->update([
                'title' => $payload['title'],
                'description' => $payload['description'],
            ]);
        }
        return $service;
    }
}