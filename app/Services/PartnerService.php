<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Partner;
use App\Models\Service;
use App\Models\Solution;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

use function PHPUnit\Framework\returnSelf;

class PartnerService 
{

    protected $MODEL;

    public function setModel(string $model): void
    {
        $this->MODEL = $model;
    }

    protected function getModel(): string
    {
        switch ($this->MODEL) {
            case 'partners':
                return Partner::class;
            case 'clients':
                return Client::class;
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
        ]);

        return $service;
    }

    public function updateService($payload, $id)
    {
        $service = $this->getService($id);

        if (isset($payload['thumbnail']) && $payload['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
            if ($service->thumbnail && $service->thumbnail != 'img/default.jpg') {
                // remove the storage part in the path
                $adjustedPath = substr($service->thumbnail, 8);
                Storage::disk('public')->delete($adjustedPath);
            }
            $path = $payload['thumbnail']->store('images', 'public');
            $payload['thumbnail'] = $path;

            $service->update([
                'thumbnail' => 'storage/' . $payload['thumbnail'],
                'title' => $payload['title'],
            ]);
        } else {
            $service->update([
                'title' => $payload['title'],
            ]);
        }
        return $service;
    }
}