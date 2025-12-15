<?php 
// There should be NO spaces or blank lines before this line.
namespace App\Models; // <--- Must be the first declaration

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Car extends Model
{
    use HasFactory;
    protected $fillable = [
        'brand', 'model', 'year', 'daily_rate', 
        'transmission', 'image', 'is_available'
    ];
}