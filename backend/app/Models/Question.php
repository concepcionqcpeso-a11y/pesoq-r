<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model {
    protected $fillable = [
        'role_key', 'role_label',
        'question_en', 'question_tl',
        'tip_en', 'tip_tl',
    ];
}