<?php

namespace App\Controller;

use App\Entity\Product;
use App\EntityBuilder\PageModelBuilder;
use App\EntityBuilder\ProductEntityBuilder;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var ProductService $productService
     */
    private $productService;
    /**
     * @var ProductEntityBuilder $productEntityBuilder
     */
    private $productEntityBuilder;

    /**
     * HomeController constructor.
     * @param ProductService $productService
     * @param ProductEntityBuilder $productEntityBuilder
     */
    public function __construct(ProductService $productService, ProductEntityBuilder $productEntityBuilder)
    {
        $this->productService = $productService;
        $this->productEntityBuilder = $productEntityBuilder;
    }

    /**
     * @Route("/", name = "home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home()
    {
        /**
         * Get products for home page rendering
         * @var Product[] $products
         */
        $products = $this->productService->findAll();

        /**
         * Convert to model for view
         * @var array $model
         */
        $model = array_merge(PageModelBuilder::toDefaultArray(), [
            'products' => $this->productEntityBuilder->toArrayList($products),
            'message' => $this->getErrorOrSuccessMessage()
        ]);

        /** Render home page twig template with products model */
        return $this->render('home.html.twig', $model);
    }

    /**
     * @Route("/initialise-product-data", name = "product_data_initialiser")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function productDataInitialiser()
    {
        /** @var Product[] $productsGenerated */
        $productsGenerated = [];

        try {
            /**
             * Generate 25 sample products
             */
            for ($i = 1; $i <= 25; ++ $i) {
                $product = new Product();
                $product->setName('Sample Product #' . $i);
                $product->setPrice(23 * $i);
                $product = $this->productService->save($product);
                $productsGenerated[] = $this->productEntityBuilder->toArray($product);
            }

            /**
             * Return json content of generated products
             */
            return $this->json($productsGenerated);
        } catch (Exception $exception) {
            throw new Exception("Failed to generated orders.", $exception);
        }
    }

    /**
     * @return array
     */
    private function getErrorOrSuccessMessage(): array
    {
        return [
            'valid' => array_key_exists('success', $_GET) || array_key_exists('error', $_GET),
            'type' => array_key_exists('success', $_GET) ? 'success' : 'error',
            'value' => array_key_exists('success', $_GET) && $_GET['success'] === 'product_added' ? 'Product Added' : (array_key_exists('error', $_GET) ? 'Error: '. $_GET['error'] : '')
        ];
    }

}
