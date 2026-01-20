<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['attachments']) && is_array($data['attachments'])) {
            
            $cleanedAttachments = [];
            
            foreach ($data['attachments'] as $file) {
                if (is_array($file) && isset($file['path'])) {
                    $cleanedAttachments[] = $file['path'];
                } 
                elseif (is_string($file)) {
                    $cleanedAttachments[] = $file;
                }
            }

            $data['attachments'] = $cleanedAttachments;
        }

        return $data;
    }
}
