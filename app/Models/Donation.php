<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $table = 'donations';

    protected $primaryKey = 'donation_id';
    public $incrementing = true;

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public static function calculateProgress($current, $total)
    {
        if ($total == 0) {
            return 0;
        }
        return round(($current / $total) * 100, 2);
    }
}
