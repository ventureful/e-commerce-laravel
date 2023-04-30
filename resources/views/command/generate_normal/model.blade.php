/**
* {{$Module}}Model class
* Author: trinhnv
* Date: {{date('Y/m/d H:i')}}
*/

namespace App\Models;

use App\Traits\Models\FillableFields;
use Illuminate\Database\Eloquent\SoftDeletes;

class {{$Module}} extends Model
{
    use SoftDeletes, FillableFields;

	protected $table = '{{$table}}';

	protected $primaryKey = 'id';

    protected $fillable = [
        @foreach($list_column as $column)
            '{{$column}}',
        @endforeach
    ];

}
