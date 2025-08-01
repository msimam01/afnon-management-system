namespace App\Services;

use App\Models\Central\CentralCommodity;
use App\Services\CentralCommodityService;

class CentralCommodityService
{
    public static function getAll()
    {
        return CentralCommodity::on('central')->get();
    }

    public static function find($id)
    {
        return CentralCommodity::on('central')->findOrFail($id);
    }

    public static function findByName($name)
    {
        return CentralCommodity::on('central')->where('name', $name)->first();
    }
}
