<?php

namespace App\Filament\Resources\DeletionRequests\Pages;

use App\Filament\Resources\DeletionRequests\DeletionRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDeletionRequest extends CreateRecord
{
    protected static string $resource = DeletionRequestResource::class;
}
