<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Validator;

trait ResourceHelper
{
    protected $use_transaction = false;
    protected $orderField = 'id';
    protected $orderSort = 'desc';

    /**
     * @return string
     */
    public function getResourceAlias()
    {
        if (property_exists($this, 'resourceName') && !empty($this->resourceName)) {
            return $this->resourceName;
        } else if (property_exists($this, 'resourceAlias') && !empty($this->resourceAlias)) {
            return $this->resourceAlias;
        } else {
            throw new \InvalidArgumentException('The property "resourceAlias" is not defined');
        }
    }

    /**
     * @return string
     */
    public function getResourceRoutesAlias()
    {
        if (property_exists($this, 'resourceRoutesAlias') && !empty($this->resourceRoutesAlias)) {
            return $this->resourceRoutesAlias;
        } elseif (property_exists($this, 'resourceName') && !empty($this->resourceName)) {
            return ADMIN_PREFIX . '::' . $this->resourceName;
        } else {
            return $this->getResourceAlias();
        }
    }

    /**
     * @return string
     */
    public function getResourceTitle($type = null, $record = null)
    {
        if (property_exists($this, 'resourceTitle') && !empty($this->resourceTitle)) {
            return $this->resourceTitle;
        } else {
            return $this->getResourceAlias();
        }
    }

    /**
     * @return mixed
     */
    public function getResourceModel()
    {
        if (property_exists($this, 'resourceModel') && !empty($this->resourceModel)) {
            return $this->resourceModel;
        } else {
            throw new \InvalidArgumentException('The property "resourceModel" is not defined');
        }
    }

    /**
     * @return mixed
     */
    public function getResourceRequest()
    {
        if (property_exists($this, 'resourceRequest') && !empty($this->resourceRequest)) {
            return $this->resourceRequest;
        } else {
            throw new \InvalidArgumentException('The property "resourceRequest" is not defined');
        }
    }

    /**
     * Validate the given request with the given rules.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $action
     * @param null $record
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function resourceValidate(Request $request, $action, $record = null)
    {
        if ($action == 'store') {
            $validation = $this->resourceStoreValidationData();
        } else {
            $validation = $this->resourceUpdateValidationData($record);
        }

        $validation['rules'] = is_array($validation['rules']) ? $validation['rules'] : [];
        $validation['messages'] = is_array($validation['messages']) ? $validation['messages'] : [];
        $validation['attributes'] = is_array($validation['attributes']) ? $validation['attributes'] : [];

        if (!isset($validation['advanced']) || !is_array($validation['advanced'])) {
            $validation['advanced'] = [];
        }

        if (count($validation['advanced'])) {
            $v = Validator::make(
                $request->all(),
                $validation['rules'],
                $validation['messages'],
                $validation['attributes']
            );


            // DOC: https://laravel.com/docs/5.6/validation
            foreach ($validation['advanced'] as $data) {
                if (isset($data['attribute']) && isset($data['rules']) && is_callable($data['callback'])) {
                    $v->sometimes($data['attribute'], $data['rules'], $data['callback']);
                }
            }

            $v->validate();

        } else {
            $this->validate($request, $validation['rules'], $validation['messages'], $validation['attributes']);
        }
    }

    /**
     * Classes using this trait have to implement this method.
     * Used to validate store.
     *
     * @return array
     */
    public function resourceStoreValidationData()
    {
        $resourceRequestClass = $this->getResourceRequest();
        $resourceRequest = new $resourceRequestClass();
        if (!$resourceRequest->authorize()) {
            abort(403);
        }
        return [
            'rules' => $resourceRequest->rules(),
            'messages' => $resourceRequest->messages(),
            'attributes' => $resourceRequest->attributes(),
            'advanced' => [],
        ];
    }

    /**
     * Classes using this trait have to implement this method.
     * Used to validate update.
     *
     * @param $record
     *
     * @return array
     */
    public function resourceUpdateValidationData($record)
    {
        $resourceRequestClass = $this->getResourceRequest();
        $resourceRequest = new $resourceRequestClass();
        if (!$resourceRequest->authorize()) {
            abort(403);
        }
        return [
            'rules' => $resourceRequest->rules($record),
            'messages' => $resourceRequest->messages(),
            'attributes' => $resourceRequest->attributes(),
            'advanced' => [],
        ];
    }

    /**
     * Classes using this trait have to implement this method.
     *
     * @param \Illuminate\Http\Request $request
     * @param null $record
     *
     * @return array
     */
    public function getValuesToSave(Request $request, $record = null)
    {
        return $request->only($this->getResourceModel()::getFillableFields());
    }

    public function alterValuesToSave(Request $request, $values, $record = null)
    {
        return $values;
    }

    /**
     * @param $record
     *
     * @return bool
     */
    public function checkDestroy($record)
    {
        return true;
    }

    /**
     * Classes using this trait have to implement this method.
     * Retrieve the list of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $perPage
     * @param string|null $search
     * @param array $paginatorData
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSearchRecords(Request $request, $perPage = 15, $search = null, $paginatorData = [])
    {
        $classModel = $this->getResourceModel();
        $model = $this->appendSearch($request, new $classModel(), $search);

        $this->getAuthorizeModel($model);

        $this->appendSort($request, $model);

        return $model->paginate($perPage)->appends($paginatorData);

    }

    /**
     * @param $record
     * @param $request
     * @param $isEdit
     *
     * @return \Illuminate\Http\Response
     */
    public function getRedirectAfterSave($record, $request, $isEdit = false)
    {
        $this->use_transaction && \DB::commit();
        return redirect(\request()->get('return_url') ?? route($this->getResourceRoutesAlias() . '.index'));
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function filterCreateViewData($data = [])
    {
        return $data;
    }

    /**
     * @param       $record
     * @param array $data
     *
     * @return array
     */
    public function filterEditViewData($record, $data = [])
    {
        return $data;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param array $data
     *
     * @return array
     */
    public function filterSearchViewData(Request $request, $data = [])
    {
        return $data;
    }

    /**
     * @param       $callbackUrl
     * @param int $status
     * @param array $headers
     * @param null $secure
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectBackTo($callbackUrl, $status = 302, $headers = [], $secure = null)
    {
        if (\request()->has('return_url')) {
            return redirect(\request()->get('return_url'));
        }
        return redirect($callbackUrl, $status, $headers, $secure);
    }

    /**
     * @return array
     */
    public function additionalListData()
    {
        return [];
    }

    /**
     * @return array
     */
    public function additionalCreateData()
    {
        return [];
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function additionalEditData($id)
    {
        return [];
    }

    public function appendSort($request, &$model)
    {
        if ($request->has('sort')) {
            $model->orderBy($request->get('sort'), $request->get('is_desc') ? 'asc' : 'desc');
        } else {
            $model->orderBy($this->orderField, $this->orderSort);
        }
    }

    public function getAuthorizeModel(&$model)
    {
        return $model;
    }

    public function getPageSize(Request $request)
    {
        $perPage = (int)$request->input('per_page', '');
        return (is_numeric($perPage) && $perPage > 0 && $perPage <= 1000) ? $perPage : DEFAULT_PAGE_SIZE;
    }

    /**
     * @param $model
     * @param $searchKey
     *
     * @return mixed
     */
    public function appendSearch(Request $request, $model, $searchKey)
    {
        return $model->search($searchKey);
    }

}
