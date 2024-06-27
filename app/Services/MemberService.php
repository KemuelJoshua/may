<?php

namespace App\Services;

use App\Models\OrganizationMember;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\returnSelf;

class MemberService 
{
    public function getMember($id)
    {
        return OrganizationMember::findOrFail($id);
    }

    public function addMember($payload)
    {
        $member = OrganizationMember::create([
            'parentId' => $payload['parentId'],
            'image' => 'storage/' . $payload['image'],
            'name' => $payload['name'],
            'lastname' => $payload['lastname'],
            'position' => $payload['position'],
        ]);

        return $member;
    }

    public function updateMember($payload, $id)
    {
        $member = $this->getMember($id);

        if (isset($payload['image']) && $payload['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($member->image && $member->image != 'img/default.jpg') {
                // remove the storage part in the path
                $adjustedPath = substr($member->image, 8);
                Storage::disk('public')->delete($adjustedPath);
            }
            
            $path = $payload['image']->store('members', 'public');
            $payload['image'] = $path;

            $member->update([
                'image' => 'storage/' . $payload['image'],
                'name' => $payload['name'],
                'lastname' => $payload['lastname'],
                'position' => $payload['position'],
                'parentId' => $payload['parentId'],
            ]);
        } else {
            $member->update([
                'name' => $payload['name'],
                'lastname' => $payload['lastname'],
                'position' => $payload['position'],
                'parentId' => $payload['parentId'],
            ]);
        }
        return $member;
    }

    public function deleteMember($id)
    {
        $member = $this->getMember($id);
        
        if ($member->image && $member->image != 'img/default.jpg') {
            // remove the storage part in the path
            $adjustedPath = substr($member->image, 8);
            Storage::disk('public')->delete($adjustedPath);
        }
        
        if ($member->children()->exists()) {
            throw new \Exception('Cannot delete member with children.');
        }

        $member->delete();

        return $member;
    }
}