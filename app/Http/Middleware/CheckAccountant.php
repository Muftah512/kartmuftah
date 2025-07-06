namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAccountant
{
   public function handle(Request $request, Closure $next)
{
    if (!auth()->check() || !auth()->user()->isAccountant()) {
        abort(403, 'غير مصرح بالوصول لهذه الصفحة');
    }
    
    return $next($request);
}