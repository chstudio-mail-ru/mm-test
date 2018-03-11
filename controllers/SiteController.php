<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use app\models\Product;
use app\models\AddProductForm;
use app\models\Order;
use app\models\AddOrderForm;
use app\models\User;
use app\models\AddUserForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage - order list.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = "";

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Displays form for add new user.
     *
     * @return string
     */
    public function actionAdduser()
    {
        $model = new AddUserForm();

        if ($model->load(Yii::$app->request->post()) && $model->add()) {
            Yii::$app->session->setFlash('addUserFormSubmitted');

            return $this->refresh();
        }

        return $this->render('adduser', [
            'model' => $model,
        ]);
    }

    /**
     * Displays list of all users.
     *
     * @return string
     */
    public function actionUsers()
    {
        $model = new User();
        $list_users = $model->listUsers();

        return $this->render('users', ['model' => $model, 'list_users' => $list_users]);
    }

    /**
     * Displays form for add new product.
     *
     * @return string
     */
    public function actionAddproduct()
    {
        $model = new AddProductForm();

        if ($model->load(Yii::$app->request->post()) && $model->add()) {
            Yii::$app->session->setFlash('addProductFormSubmitted');

            return $this->refresh();
        }

        return $this->render('addproduct', [
            'model' => $model,
        ]);
    }

    /**
     * Displays form for save product.
     *
     * @return string
     */
    public function actionEditproduct()
    {
        $model = new AddProductForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->refresh();
        }

        if (Yii::$app->request->get('id') > 0 && !Yii::$app->request->post() && $model->loadProduct(Yii::$app->request->get('id'))) {
            return $this->render('editproduct', [
                'model' => $model,
            ]);
        }

        Yii::$app->session->setFlash('saveProductFormSubmitted');
        return $this->render('editproduct', [
            'model' => $model,
        ]);
    }

    /**
     * Displays list of products.
     *
     * @return string
     */
    public function actionProducts()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->session->setFlash('filterFormSubmitted');
            $list_products = $model->listProducts();

            return $this->render('products', ['model' => $model, 'list_products' => $list_products]);
        }

        $list_products = $model->listProducts();
        return $this->render('products', ['model' => $model, 'list_products' => $list_products]);
    }

    /**
     * Displays form for add new order.
     *
     * @return string
     */
    public function actionAddorder()
    {
        $model = new AddOrderForm();
        $userModel = new User();
        $user_list = $userModel->listUsers();
        $user_items = ArrayHelper::map($user_list,'id','name');
        $productModel = new Product();
        $product_list = $productModel->listProducts();
        $product_items = ArrayHelper::map($product_list,'id','name');

        if ($model->load(Yii::$app->request->post()) && $model->add()) {
            Yii::$app->session->setFlash('addOrderFormSubmitted');

            return $this->refresh();
        }

        return $this->render('addorder', [
            'model' => $model,
            'user_list' => $user_items,
            'product_list' => $product_items,
        ]);
    }
}
