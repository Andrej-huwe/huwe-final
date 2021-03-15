<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Backend\BlogCategories\DeleteBlogCategoriesRequest;
use App\Http\Requests\Backend\BlogCategories\ManageBlogCategoriesRequest;
use App\Http\Requests\Backend\BlogCategories\StoreBlogCategoriesRequest;
use App\Http\Requests\Backend\BlogCategories\UpdateBlogCategoriesRequest;
use App\Http\Resources\BlogCategoriesResource;
use App\Models\BlogCategory;
use App\Repositories\Backend\BlogCategoriesRepository;
use Illuminate\Http\Response;

/**
 * @group blog Categories Management
 *
 * Class BlogCategoriesController
 *
 * APIs for blog Categories Management
 *
 * @authenticated
 */
class BlogCategoriesController extends APIController
{
    /**
     * Repository.
     *
     * @var BlogCategoriesRepository
     */
    protected $repository;

    /**
     * __construct.
     *
     * @param $repository
     */
    public function __construct(BlogCategoriesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all blog Categories.
     *
     * This endpoint provides a paginated list of all blog categories. You can customize how many records you want in each
     * returned response as well as sort records based on a key in specific order.
     *
     * @queryParam page Which page to show. Example: 12
     * @queryParam per_page Number of records per page. (use -1 to retrieve all) Example: 20
     * @queryParam order_by Order by database column. Example: created_at
     * @queryParam order Order direction ascending (asc) or descending (desc). Example: asc
     *
     * @responseFile status=401 scenario="API token not provided" responses/unauthenticated.json
     * @responseFile responses/blog-category/blog-category-list.json
     *
     * @param ManageBlogCategoriesRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ManageBlogCategoriesRequest $request)
    {
        $collection = $this->repository->retrieveList($request->all());

        return BlogCategoriesResource::collection($collection);
    }

    /**
     * Gives a specific blog Category.
     *
     * This endpoint provides you a single blog Category
     * The blog Category is identified based on the ID provided as url parameter.
     *
     * @urlParam id required The ID of the blog Category
     *
     * @responseFile status=401 scenario="API token not provided" responses/unauthenticated.json
     * @responseFile responses/blog-category/blog-category-show.json
     *
     * @param ManageBlogCategoriesRequest $request
     * @param BlogCategory $blogCategory
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ManageBlogCategoriesRequest $request, BlogCategory $blogCategory)
    {
        return new BlogCategoriesResource($blogCategory);
    }

    /**
     * Create a new blog Category.
     *
     * This endpoint lets you create new blog Category
     *
     * @responseFile status=401 scenario="API token not provided" responses/unauthenticated.json
     * @responseFile status=201 responses/blog-category/blog-category-store.json
     *
     * @param StoreBlogCategoriesRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBlogCategoriesRequest $request)
    {
        $blogCategory = $this->repository->create($request->validated());

        return (new BlogCategoriesResource($blogCategory))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Update blog Category.
     *
     * This endpoint allows you to update existing blog Category with new data.
     * The blog Category to be updated is identified based on the ID provided as url parameter.
     *
     * @urlParam id required The ID of the blog Category
     *
     * @responseFile status=401 scenario="API token not provided" responses/unauthenticated.json
     * @responseFile status=200 responses/blog-category/blog-category-update.json
     *
     * @param UpdateBlogCategoriesRequest $request
     * @param BlogCategory $blogCategory
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBlogCategoriesRequest $request, BlogCategory $blogCategory)
    {
        $blogCategory = $this->repository->update($blogCategory, $request->validated());

        return new BlogCategoriesResource($blogCategory);
    }

    /**
     * Delete blog Category.
     *
     * This endpoint allows you to delete a blog Category
     * The blog Category to be deleted is identified based on the ID provided as url parameter.
     *
     * @urlParam id required The ID of the blog Category
     *
     * @responseFile status=401 scenario="API token not provided" responses/unauthenticated.json
     * @responseFile status=204 scenario="When the record is deleted" responses/blog-category/blog-category-destroy.json
     *
     * @param DeleteBlogCategoriesRequest $request
     * @param BlogCategory $blogCategory
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteBlogCategoriesRequest $request, BlogCategory $blogCategory)
    {
        $this->repository->delete($blogCategory);

        return response()->noContent();
    }
}
