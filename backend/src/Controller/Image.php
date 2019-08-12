<?php
/**
 * Created by PhpStorm.
 * User: kasi
 * Date: 29.01.2019
 * Time: 15:49
 */


namespace App\Controller;

use App\ControlerBase;
use App\Manager;
use Slim\Http\Request;
use Slim\Http\Response;

class Image extends ControlerBase {


    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function saveAction(Request $request, Response $response, array $args){
        /** @var \App\Repository\Images $repo */
        $repo = Manager::getClassInstance('repository.images');
        $id = $request->getParam('id');
        $title = $request->getParam('title');
        $info = $request->getParam('info');
        $tag = $request->getParam('tag','');
        $longitude = $request->getParam('longitude','');
        $latitude = $request->getParam('latitude','');
        if ($id){
            try {
                $image = $repo->getImage($id);
                if ($image){
                    $image->setTitle($title);
                    $image->setInfo($info);
                    $image->setTag($tag);
                    $image->setLatitude($latitude);
                    $image->setLongitude($longitude);
                    $repo->updateImage($image);
                    return $response->withJson([
                        'success'=>true,
                        'image'=>$image,
                    ]);

                }
                return $response->withJson([
                    'success'=>false,
                    'message'=>'Bild nicht gefunden'
                ]);
            }
            catch(\Exception $e){
                return $response->withJson( [
                    'success'=>false,
                    'message'=>$e->getMessage(),
                    'file'=>$e->getFile(),
                    'line'=>$e->getLine()
                ]);
            }
        }
        else {
            $file = fopen($request->getParam('href'),'rb');
            $image = new \App\Model\Image([
                'title'=>$title,
                'info'=>$info,
                'tag' => $tag,
                'latitude'=>$latitude,
                'longitude'=>$longitude,
            ]);
            try {
                $img = $repo->addImage($image, $file);
                return $response->withJson([
                    'success'=>true,
                    'image'=>$img,
                ]);
            }
            catch (\Exception $e){
                return $response->withJson( [
                    'success'=>false,
                    'message'=>$e->getMessage(),
                    'file'=>$e->getFile(),
                    'line'=>$e->getLine()
                ]);
            }
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function listAction(Request $request, Response $response, array $args):Response {
        /** @var \App\Repository\Images $repo */
        $repo = Manager::getClassInstance('repository.images');
        return $response->withJson([
            'success'=> true,
            'data' => $repo->getImageList($request->getParams())
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function deleteAction(Request $request, Response $response, array $args):Response {
        /** @var \App\Repository\Images $repo */
        $repo = Manager::getClassInstance('repository.images');
        $image = $repo->getImage($request->getParam('id'));
        if (!$image){
            return $response->withJson(['success'=>false,'message'=>'Invalid Image']);
        }
        return $response->withJson([
            'success'=>$repo->deleteImage($image)
        ]);
    }
}