<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModuleMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create module command';

    protected $module_path;

    protected $module_name;

    protected $module_name_lower;

    protected $module_name_plural;

    protected $module_name_plural_lower;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->initializeProperties();
        $this->createFolders();
        $this->createController();
        $this->createFormRequests();
        $this->createProvider();
        $this->createRepository();
        $this->createRepositoryInterface();
        $this->createFakeRepositoryInterface();
        $this->createFakeRepository();
        $this->createRouteApi();
        $this->createServiceInterface();
        $this->createGetAllService();
        $this->createGetByIdService();
        $this->createCreateService();
        $this->createUpdateService();
        $this->createDeleteService();
        $this->createModel();
        $this->createFoldersTest();
        $this->createUnitTest();
        $this->createUnitCreateServiceTest();
        $this->createIntegrationCreateServiceTest();
        $this->createMigration();
        $this->registerProvider();
        $this->givePermissions();

        $this->info('Created module: ' . $this->argument('module') . PHP_EOL);
    }

    private function initializeProperties()
    {
        $this->module_path =
            config('modules')['path'] . '/' . $this->argument('module');

        $this->module_name = Str::ucfirst($this->argument('module'));

        $this->module_name_lower = Str::lower($this->argument('module'));

        $this->module_name_plural = Str::plural($this->argument('module'), 2);

        $this->module_name_plural_lower = Str::lower($this->module_name_plural);
    }

    private function createFolders()
    {
        $config = config('modules');

        $folders = $config['folders'];

        foreach ($folders as $folder) {
            $folder_path =
                $config['path'] .
                '/' .
                $this->module_name .
                '/' .
                $folder['path'];

            File::makeDirectory($folder_path, 0777, true, true);

            if ($folder['gitkeep']) {
                file_put_contents($folder_path . '/.gitkeep', '');
            }
        }
    }

    private function createController()
    {
        $path =
            $this->module_path .
            '/Http/Controllers/' .
            $this->module_name .
            'Controller.php';

        $content =
            '<?php

namespace Modules\\' .
            $this->module_name .
            '\Http\Controllers;

use App\Models\\' .
            $this->module_name .
            ';
use App\Facades\ExecuteService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\\' .
            $this->module_name .
            '\Http\Requests\Create' .
            $this->module_name .
            'Request;
use Modules\\' .
            $this->module_name .
            '\Http\Requests\Update' .
            $this->module_name .
            'Request;
use Modules\\' .
            $this->module_name .
            '\Services\GetAll' .
            $this->module_name_plural .
            'Service;
use Modules\\' .
            $this->module_name .
            '\Services\Create' .
            $this->module_name .
            'Service;
use Modules\\' .
            $this->module_name .
            '\Services\Update' .
            $this->module_name .
            'Service;
use Modules\\' .
            $this->module_name .
            '\Services\Get' .
            $this->module_name .
            'ByIdService;
use Modules\\' .
            $this->module_name .
            '\Services\Delete' .
            $this->module_name .
            'ByIdService;


class ' .
            $this->module_name .
            'Controller extends Controller
{
    public function index(): Collection | LengthAwarePaginator
    {
        return ExecuteService::execute(service: GetAll' .
            $this->module_name_plural .
            'Service::class);
    }

    public function edit(string $id): ?' .
            $this->module_name .
            '
    {
        return ExecuteService::execute(service: Get' .
            $this->module_name .
            'ByIdService::class, parameters: [
            "id" => $id,
        ]);
    }

    public function store(Create' .
            $this->module_name .
            'Request $request): ' .
            $this->module_name .
            '
    {
        return ExecuteService::execute(service: Create' .
            $this->module_name .
            'Service::class, parameters: [
            "attributes" => $request->all(),
        ]);
    }

    public function update(string $id, Update' .
            $this->module_name .
            'Request $request): bool
    {
        $' .
            $this->module_name_lower .
            ' = ExecuteService::execute(service: Get' .
            $this->module_name .
            'ByIdService::class, parameters: [
            "id" => $id,
        ]);

        return ExecuteService::execute(service: Update' .
            $this->module_name .
            'Service::class, parameters: [
            "' .
            $this->module_name_lower .
            '" => $' .
            $this->module_name_lower .
            ',
            "attributes" => $request->all(),
        ]);
    }

    public function delete(string $id): bool
    {
        return ExecuteService::execute(service: Delete' .
            $this->module_name .
            'ByIdService::class, parameters: [
            "id" => $id,
        ]);
    }
}';

        file_put_contents($path, $content);
    }

    private function createFormRequests()
    {
        $paths = [
            'Create' =>
                $this->module_path .
                '/Http/Requests/Create' .
                $this->module_name .
                'Request.php',

            'Update' =>
                $this->module_path .
                '/Http/Requests/Update' .
                $this->module_name .
                'Request.php',
        ];

        foreach ($paths as $key => $path) {
            $class_name = $key . $this->module_name . 'Request';

            $content =
                '<?php

namespace Modules\\' .
                $this->module_name .
                '\Http\Requests;

use Urameshibr\Requests\FormRequest;

class ' .
                $class_name .
                ' extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // "name" => "required",
        ];
    }
}
';

            file_put_contents($path, $content);
        }
    }

    private function createProvider()
    {
        $path =
            $this->module_path .
            '/Providers//' .
            $this->module_name .
            'ServiceProvider.php';

        $content =
            '<?php

namespace Modules\\' .
            $this->module_name .
            '\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\\' .
            $this->module_name .
            '\Repositories\\' .
            $this->module_name .
            'Repository;
use Modules\\' .
            $this->module_name .
            '\Repositories\Contracts\\' .
            $this->module_name .
            'RepositoryInterface;

class ' .
            $this->module_name .
            'ServiceProvider extends ServiceProvider
{
    protected string $moduleNamespace = "Modules\\' .
            $this->module_name .
            '\Http\Controllers";

    public function register(): void
    {
        $this->loadApiRoutes();
        $this->bindInterfaces();
        $this->registerViews();
    }

    private function loadApiRoutes(): void
    {
        $this->app->router->group(
            [
                "namespace" => $this->moduleNamespace,
            ],
            function ($router) {
                require __DIR__ . "/../Routes/api.php";
            }
        );
    }

    private function bindInterfaces(): void
    {
        $this->app->bind(
            ' .
            $this->module_name .
            'RepositoryInterface::class,
            ' .
            $this->module_name .
            'Repository::class
        );
    }

    public function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . "/../Resources/views", "' .
            $this->module_name_lower .
            '");
    }
}
';

        file_put_contents($path, $content);
    }

    private function createRepository()
    {
        $path =
            $this->module_path .
            '/Repositories//' .
            $this->module_name .
            'Repository.php';

        $content =
            '<?php

namespace Modules\\' .
            $this->module_name .
            '\Repositories;

use App\Models\\' .
            $this->module_name .
            ';
use App\Support\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\\' .
            $this->module_name .
            '\Repositories\Contracts\\' .
            $this->module_name .
            'RepositoryInterface;

class ' .
            $this->module_name .
            'Repository extends BaseRepository implements ' .
            $this->module_name .
            'RepositoryInterface
{
    /**
     * @var [Model]
     */
    protected $model = ' .
            $this->module_name .
            '::class;

    public function filter(
        ?string $filter,
        array $columns = ["*"],
        array $relationships = [],
        ?int $page = null,
        string $orderBy = "id"
    ): Collection | LengthAwarePaginator {
        $collection = $this->model->select($columns)->with($relationships);

        if ($filter) {
            $collection = $collection->where("name", "ilike", "%". $filter ."%");
        }

        if ($page) {
            request()->merge(["page" => $page]);

            return $collection->paginate();
        }

        return $collection->get();
    }
}
';

        file_put_contents($path, $content);
    }

    private function createRepositoryInterface()
    {
        $path =
            $this->module_path .
            '/Repositories/Contracts/' .
            $this->module_name .
            'RepositoryInterface.php';

        $content =
            '<?php

namespace Modules\\' .
            $this->module_name .
            '\Repositories\Contracts;

use App\Support\Contracts\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ' .
            $this->module_name .
            'RepositoryInterface extends RepositoryInterface
{
    public function filter(
        ?string $filter,
        array $columns = ["*"],
        array $relationships = [],
        ?int $page = null,
        string $orderBy = "id"
    ): Collection | LengthAwarePaginator;
}
';

        file_put_contents($path, $content);
    }

    private function createFakeRepositoryInterface()
    {
        $path =
            $this->module_path .
            '/Repositories/Contracts/Fake' .
            $this->module_name .
            'RepositoryInterface.php';

        $content =
            '<?php

namespace Modules\\' .
            $this->module_name .
            '\Repositories\Contracts;

interface Fake' .
            $this->module_name .
            'RepositoryInterface extends ' .
            $this->module_name .
            'RepositoryInterface
{
}
';

        file_put_contents($path, $content);
    }

    private function createFakeRepository()
    {
        $path =
            $this->module_path .
            '/Repositories/Fakes/Fake' .
            $this->module_name .
            'Repository.php';

        $content =
            '<?php

namespace Modules\\' .
            $this->module_name .
            '\Repositories\Fakes;

use App\Models\\' .
            $this->module_name .
            ';
use App\Support\Fakes\FakeBaseRepository;
use App\Support\Traits\UsesSingleton;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\\' .
            $this->module_name .
            '\Repositories\Contracts\Fake' .
            $this->module_name .
            'RepositoryInterface;

class Fake' .
            $this->module_name .
            'Repository extends FakeBaseRepository implements
    Fake' .
            $this->module_name .
            'RepositoryInterface
{
    use UsesSingleton;

    protected string $model = ' .
            $this->module_name .
            '::class;

    public function filter(
        ?string $filter,
        array $columns = ["*"],
        array $relationships = [],
        ?int $page = null,
    ): Collection | LengthAwarePaginator
    {
        return new Collection([]);
    }
}
';

        file_put_contents($path, $content);
    }

    private function createRouteApi()
    {
        $path = $this->module_path . '/Routes/api.php';

        $content =
            '<?php

$router->group(["prefix" => "api/' .
            $this->module_name_plural_lower .
            '"], function () use ($router) {
    $router->group(
        ["middleware" => ["json.response", "auth"]],
        function () use ($router) {
            $router->get("/", "' .
            $this->module_name .
            'Controller@index");
            $router->post("/", "' .
            $this->module_name .
            'Controller@store");
            $router->get("/{id}", "' .
            $this->module_name .
            'Controller@edit");
            $router->put("/{id}", "' .
            $this->module_name .
            'Controller@update");
            $router->delete("/{id}", "' .
            $this->module_name .
            'Controller@delete");
        }
    );
});
';

        file_put_contents($path, $content);
    }

    private function createServiceInterface()
    {
        $path =
            $this->module_path .
            '/Services/Contracts/Create' .
            $this->module_name .
            'ServiceInterface.php';

        $content =
            '<?php

namespace Modules\\' .
            $this->module_name .
            '\Services\Contracts;

use App\Support\Contracts\ServiceInterface;

interface Create' .
            $this->module_name .
            'ServiceInterface extends ServiceInterface
{
}
';

        file_put_contents($path, $content);
    }

    private function createGetAllService()
    {
        $path =
            $this->module_path .
            '/Services/GetAll' .
            $this->module_name_plural .
            'Service.php';

        $content =
            '<?php

namespace Modules\\' .
            $this->module_name .
            '\Services;

use App\Support\Contracts\RepositoryInterface;
use App\Support\Contracts\ServiceInterface;
use Modules\\' .
            $this->module_name .
            '\Repositories\Contracts\\' .
            $this->module_name .
            'RepositoryInterface;

class GetAll' .
            $this->module_name_plural .
            'Service implements ServiceInterface
{
    private ' .
            $this->module_name .
            'RepositoryInterface $repository;

    private array $parameters;

    public function __construct(array $parameters = [])
    {
        $this->repository = app(' .
            $this->module_name .
            'RepositoryInterface::class);

        $this->parameters = $parameters;
    }

    public function setRepository(RepositoryInterface $repository): void
    {
        $this->repository = $repository;
    }

    public function setParameters(array $parameters = []): void
    {
        $this->parameters = $parameters;
    }

    public function execute(): mixed
    {
        $request = request();

        $filter = $this->parameters["filter"] ?? $request->filter;

        $columns = $this->setColumns();

        $relationships =
            $this->parameters["relationships"] ??
            ($request->relationships ?? []);

        $page = $this->parameters["page"] ?? $request->page;

        return $this->repository->filter(
            $filter,
            $columns,
            $relationships,
            $page
        );
    }

    private function setColumns(): array
    {
        $request_columns = request()->columns
            ? explode(",", request()->columns)
            : null;

        return $this->parameters["columns"] ?? ($request_columns ?? ["*"]);
    }
}
';

        file_put_contents($path, $content);
    }

    private function createGetByIdService()
    {
        $path =
            $this->module_path .
            '/Services/Get' .
            $this->module_name .
            'ByIdService.php';

        $content =
            '<?php

namespace Modules\\' .
            $this->module_name .
            '\Services;

use Error;
use App\Support\Contracts\RepositoryInterface;
use App\Support\Contracts\ServiceInterface;
use Modules\\' .
            $this->module_name .
            '\Repositories\Contracts\\' .
            $this->module_name .
            'RepositoryInterface;

class Get' .
            $this->module_name .
            'ByIdService implements ServiceInterface
{
    private ' .
            $this->module_name .
            'RepositoryInterface $repository;

    private array $parameters;

    public function __construct(array $parameters = [])
    {
        $this->repository = app(' .
            $this->module_name .
            'RepositoryInterface::class);

        $this->parameters = $parameters;
    }

    public function setRepository(RepositoryInterface $repository): void
    {
        $this->repository = $repository;
    }

    public function setParameters(array $parameters = []): void
    {
        $this->parameters = $parameters;
    }

    public function execute(): mixed
    {
        $this->validateParameters();

        $columns = $this->setColumns();

        $relationships = $this->parameters["relationships"] ?? [];

        return $this->repository->findById(
            $this->parameters["id"],
            $columns,
            $relationships
        );
    }

    private function validateParameters()
    {
        if (!isset($this->parameters["id"])) {
            throw new Error("The id is required");
        }
    }

    private function setColumns(): array
    {
        $request_columns = request()->columns
            ? explode(",", request()->columns)
            : null;

        return $this->parameters["columns"] ?? ($request_columns ?? ["*"]);
    }
}
';

        file_put_contents($path, $content);
    }

    private function createCreateService()
    {
        $path =
            $this->module_path .
            '/Services/Create' .
            $this->module_name .
            'Service.php';

        $content =
            '<?php

namespace Modules\\' .
            $this->module_name .
            '\Services;

use Error;
use App\Support\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Modules\\' .
            $this->module_name .
            '\Repositories\Contracts\\' .
            $this->module_name .
            'RepositoryInterface;
use Modules\\' .
            $this->module_name .
            '\Repositories\\' .
            $this->module_name .
            'Repository;
use Modules\\' .
            $this->module_name .
            '\Services\Contracts\Create' .
            $this->module_name .
            'ServiceInterface;

class Create' .
            $this->module_name .
            'Service implements Create' .
            $this->module_name .
            'ServiceInterface
{
    private ' .
            $this->module_name .
            'RepositoryInterface $repository;

    private array $parameters;

    public function __construct()
    {
        $this->repository = app(' .
            $this->module_name .
            'Repository::class);
    }

    public function setRepository(RepositoryInterface $repository): void
    {
        $this->repository = $repository;
    }

    public function setParameters(array $parameters = []): void
    {
        $this->parameters = $parameters;
    }

    public function execute(): mixed
    {
        $this->validateParameters();

        return $this->repository->create($this->parameters["attributes"]);
    }

    private function validateParameters(): void
    {
        $attributes = $this->parameters["attributes"];

        $validator = Validator::make($attributes, [
            "name" => "required",
        ]);

        if ($validator->fails()) {
            throw new Error(
                "The " . $validator->errors()->first() . " is incorrect"
            );
        }
    }
}
';

        file_put_contents($path, $content);
    }

    private function createUpdateService()
    {
        $path =
            $this->module_path .
            '/Services/Update' .
            $this->module_name .
            'Service.php';

        $content =
            '<?php

namespace Modules\\' .
            $this->module_name .
            '\Services;

use Error;
use App\Models\\' .
            $this->module_name .
            ';
use App\Support\Contracts\RepositoryInterface;
use App\Support\Contracts\ServiceInterface;
use Illuminate\Support\Facades\Validator;
use Modules\\' .
            $this->module_name .
            '\Repositories\Contracts\\' .
            $this->module_name .
            'RepositoryInterface;

class Update' .
            $this->module_name .
            'Service implements ServiceInterface
{
    private ' .
            $this->module_name .
            'RepositoryInterface $repository;

    private array $parameters;

    public function __construct()
    {
        $this->repository = app(' .
            $this->module_name .
            'RepositoryInterface::class);
    }

    public function setRepository(RepositoryInterface $repository): void
    {
        $this->repository = $repository;
    }

    public function setParameters(array $parameters = []): void
    {
        $this->parameters = $parameters;
    }

    public function execute(): mixed
    {
        $this->validateParameters();

        return $this->repository->update(
            $this->parameters["attributes"],
            $this->parameters["' .
            $this->module_name_lower .
            '"]->id
        );
    }

    private function validateParameters()
    {
        if (
            !isset($this->parameters["' .
            $this->module_name_lower .
            '"]) ||
            !$this->parameters["' .
            $this->module_name_lower .
            '"] instanceof ' .
            $this->module_name .
            '
        ) {
            throw new Error(
                "The ' .
            $this->module_name_lower .
            ' is required and must to be instance of ' .
            $this->module_name .
            '"
            );
        }

        if (!isset($this->parameters["attributes"])) {
            throw new Error("The attributes are required");
        }

        $validator = Validator::make($this->parameters["attributes"], [
            "name" => "required",
        ]);

        if ($validator->fails()) {
            throw new Error(
                "The " . $validator->errors()->first() . " is incorrect"
            );
        }
    }
}
';

        file_put_contents($path, $content);
    }

    private function createDeleteService()
    {
        $path =
            $this->module_path .
            '/Services/Delete' .
            $this->module_name .
            'ByIdService.php';

        $content =
            '<?php

namespace Modules\\' .
            $this->module_name .
            '\Services;

use Error;
use App\Support\Contracts\RepositoryInterface;
use App\Support\Contracts\ServiceInterface;
use Modules\\' .
            $this->module_name .
            '\Repositories\Contracts\\' .
            $this->module_name .
            'RepositoryInterface;

class Delete' .
            $this->module_name .
            'ByIdService implements ServiceInterface
{
    private ' .
            $this->module_name .
            'RepositoryInterface $repository;

    private array $parameters;

    public function __construct(array $parameters = [])
    {
        $this->repository = app(' .
            $this->module_name .
            'RepositoryInterface::class);

        $this->parameters = $parameters;
    }

    public function setRepository(RepositoryInterface $repository): void
    {
        $this->repository = $repository;
    }

    public function setParameters(array $parameters = []): void
    {
        $this->parameters = $parameters;
    }

    public function execute(): mixed
    {
        $this->validateParameters();

        return $this->repository->delete($this->parameters["id"]);
    }

    private function validateParameters()
    {
        if (!isset($this->parameters["id"])) {
            throw new Error("The id is required");
        }
    }
}
';

        file_put_contents($path, $content);
    }

    private function createModel()
    {
        $path = base_path('app/Models/' . $this->argument('module') . '.php');

        if (File::exists($path)) {
            return;
        }

        $content =
            '<?php

namespace App\Models;

use App\Models\Traits\UsesUuid;
use App\Models\Traits\UsesSerializeDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ' .
            $this->module_name .
            ' extends Model
{
    use SoftDeletes, UsesUuid, UsesSerializeDates;

    protected $table = "' .
            $this->module_name_plural_lower .
            '";

    protected $primaryKey = "id";

    protected $fillable = ["id"];
}
';

        file_put_contents($path, $content);
    }

    private function createFoldersTest()
    {
        $folders = [
            ['path' => base_path('tests/Integration/' . $this->module_name)],
            ['path' => base_path('tests/Unit/' . $this->module_name)],
        ];

        foreach ($folders as $folder) {
            File::makeDirectory($folder['path'], 0777, true, true);
        }
    }

    private function createUnitTest()
    {
        $file = base_path(
            'tests/Unit/' .
                $this->module_name .
                '/' .
                $this->module_name .
                'Test.php'
        );

        if (File::exists($file)) {
            return;
        }

        $content =
            '<?php

namespace Tests\Unit;

use TestCase;
use App\Models\\' .
            $this->module_name .
            ';

class ' .
            $this->module_name .
            'Test extends TestCase
{
    public function test_' .
            $this->module_name_lower .
            '_fillable()
    {
        $' .
            $this->module_name_lower .
            ' = new ' .
            $this->module_name .
            '();

        $expected = ["id"];

        $array_compared = array_diff($expected, $' .
            $this->module_name_lower .
            '->getFillable());

        $this->assertEquals(0, count($array_compared));
    }
}
        ';

        file_put_contents($file, $content);
    }

    private function createUnitCreateServiceTest()
    {
        $file = base_path(
            'tests/Unit/' .
                $this->module_name .
                '/Create' .
                $this->module_name .
                'ServiceTest.php'
        );

        if (File::exists($file)) {
            return;
        }

        $content =
            '<?php

namespace Tests\Unit\\' .
            $this->module_name .
            ';

use TestCase;
use Illuminate\Support\Str;
use App\Facades\ExecuteService;
use Modules\\' .
            $this->module_name .
            '\Repositories\Contracts\Fake' .
            $this->module_name .
            'RepositoryInterface;
use Modules\\' .
            $this->module_name .
            '\Services\Contracts\Create' .
            $this->module_name .
            'ServiceInterface;
use Modules\\' .
            $this->module_name .
            '\Services\Get' .
            $this->module_name .
            'ByIdService;

class Create' .
            $this->module_name .
            'ServiceTest extends TestCase
{
    public function test_create_' .
            $this->module_name_lower .
            '()
    {
        $uuid = Str::uuid();

        $' .
            $this->module_name_lower .
            ' = ExecuteService::execute(
            service:
            Create' .
            $this->module_name .
            'ServiceInterface::class,
            parameters:
            [
                "attributes" => ["id" => $uuid],
            ],
            repository:
            Fake' .
            $this->module_name .
            'RepositoryInterface::class
        );

        $find_' .
            $this->module_name_lower .
            ' = ExecuteService::execute(
            service: Get' .
            $this->module_name .
            'ByIdService::class,
            parameters: ["id" => $' .
            $this->module_name_lower .
            '->id],
            repository: Fake' .
            $this->module_name .
            'RepositoryInterface::class
        );

        $this->assertArrayHasKey("id", $find_' .
            $this->module_name_lower .
            '->getAttributes());
        $this->assertEquals("id", $uuid);
    }
}
';

        file_put_contents($file, $content);
    }

    private function createIntegrationCreateServiceTest()
    {
        $file = base_path(
            'tests/Integration/' .
                $this->module_name .
                '/Create' .
                $this->module_name .
                'ServiceTest.php'
        );

        if (File::exists($file)) {
            return;
        }

        $content =
            '<?php

namespace Tests\Integration\\' .
            $this->module_name .
            ';

use TestCase;
use Illuminate\Support\Str;
use App\Facades\ExecuteService;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Modules\\' .
            $this->module_name .
            '\Services\Contracts\Create' .
            $this->module_name .
            'ServiceInterface;

class Create' .
            $this->module_name .
            'ServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_create_' .
            $this->module_name_lower .
            '()
    {
        $uuid = Str::uuid();

        ExecuteService::execute(
            service:
            Create' .
            $this->module_name .
            'ServiceInterface::class,
            parameters:
            [
                "attributes" => ["id" => $uuid],
            ],
        );

        $this->seeInDatabase("products", [
            "id" => $uuid,
        ]);
    }
}
';

        file_put_contents($file, $content);
    }

    private function createMigration()
    {
        try {
            Artisan::call(
                'make:migration create_' .
                    $this->module_name_plural_lower .
                    '_table'
            );
        } catch (Exception $exception) {
            // var_dump($exception);
        }
    }

    private function registerProvider()
    {
        if ($this->isRegisteredProvider()) {
            return;
        }

        $app_php_lines = explode(
            PHP_EOL,
            file_get_contents(base_path('bootstrap/app.php'))
        );

        $new_app_php_lines = [];

        $added_service_provider = false;
        $session_application_providers = false;

        foreach ($app_php_lines as $line) {
            $new_app_php_lines[] = $line;

            if ($line == '| Load Application Service Providers') {
                $session_application_providers = true;
            }

            if (
                $session_application_providers &&
                !$line &&
                !$added_service_provider
            ) {
                $new_app_php_lines[] =
                    '$app->register(Modules\\' .
                    $this->module_name .
                    '\Providers\\' .
                    $this->module_name .
                    'ServiceProvider::class);';

                $added_service_provider = true;
            }
        }

        file_put_contents(
            base_path('bootstrap/app.php'),
            implode(PHP_EOL, $new_app_php_lines)
        );
    }

    private function isRegisteredProvider()
    {
        $app_php_lines = explode(
            PHP_EOL,
            file_get_contents(base_path('bootstrap/app.php'))
        );

        $is_registered = false;

        $app_service_provider =
            '$app->register(Modules\\' .
            $this->module_name .
            '\Providers\\' .
            $this->module_name .
            'ServiceProvider::class);';

        foreach ($app_php_lines as $line) {
            if ($line == $app_service_provider) {
                $is_registered = true;
            }
        }

        return $is_registered;
    }

    private function givePermissions()
    {
        shell_exec('chmod -R 777 app/Modules/' . $this->module_name);
        shell_exec('chmod 777 app/Models/' . $this->module_name . '.php');
        shell_exec('chmod -R 777 tests/Integration/' . $this->module_name);
        shell_exec('chmod -R 777 tests/Unit/' . $this->module_name);
    }
}
