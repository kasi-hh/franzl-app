<?php
/**
 * Created by PhpStorm.
 * User: kasi
 * Date: 29.01.2019
 * Time: 14:12
 */

namespace App\Repository;


use App\Lib\FileLib;
use App\Model\Image;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Slim\Http\Stream;

class Images {
    /**
     * @var \Slim\Container
     */
    protected $di;

    public function __construct(\Slim\Container $di = null) {
        if (null !== $di) {
            $this->setDi($di);
        }
    }

    public function setDi(\Slim\Container $di) {
        $this->di = $di;
    }

    /**
     * @return Connection
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function getDb(): Connection {
        return $this->di->get('db');
    }

    /**
     * @param array $params
     * @return Image[]
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function getImageList(array $params = []): array {
        $db = $this->getDb();
        $builder = $db->createQueryBuilder();
        $builder->select('*')
            ->from('franzl')
            ->orderBy('created', 'DESC');
        $stmt = $db->query($builder->getSQL());
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $result = [];
        while ($row = $stmt->fetch()) {
            $result[] = new \App\Model\Image($row);
        }
        return $result;
    }


    /**
     * @param $id
     * @return Image
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function getImage($id): ?Image {
        $db = $this->getDb();
        /*
        $stmt = $db->prepare($db->createQueryBuilder()->select('*')->from('franzl')->where('id=:id')->getSQL());
        $stmt->execute([':id' => $id]);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $stmt->fetch();
        */
        $data = $db->fetchAssoc('select * from franzl where id=:id', ['id' => $id]);
        if ($data) {
            return new Image($data);
        }
        return null;
    }

    /**
     * @param \App\Model\Image $image
     * @param $imageData
     * @param $dir
     * @return Image
     * @throws ConnectionException
     * @throws \Exception
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function addImage(\App\Model\Image $image, $stream): ?Image {
        $db = $this->getDb();
        $db->beginTransaction();
        $data = [
            'title' => $image->getTitle(),
            'info' => $image->getInfo(),
            'created' => (new \DateTime())->format('Y-m-d H:i:s'),
            'tag' => $image->getTag(),
            'latitude' => $image->getLatitude(),
            'longitude' => $image->getLongitude(),
        ];
        try {
            $db->insert('franzl', $data);
            $id = $db->lastInsertId();
            $filename = 'image-' . $id . '.jpg';
            if (file_put_contents(IMAGE_ROOT . DIRECTORY_SEPARATOR . $filename, $stream) === false) {
                throw new \Exception("Can not write file: $filename");
            }
            $db->update('franzl', ['href' => '/images/' . $filename], ['id' => $id]);
            $result = $this->getImage($id);
            $db->commit();
            return $result;
        } catch (\Exception $e) {
            unlink($filename);
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * @param Image $image
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Exception
     */
    public function updateImage(\App\Model\Image $image) {
        $data = [
            'title' => $image->getTitle(),
            'info' => $image->getInfo(),
            'tag' => $image->getTag(),
            'latitude' => $image->getLatitude(),
            'longitude' => $image->getLongitude(),
        ];
        $db = $this->getDb();
        $db->beginTransaction();
        try {
            $db->update('franzl', $data, ['id' => $image->getId()]);
            $db->commit();
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * @param Image $image
     * @return bool
     * @throws ConnectionException
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function deleteImage(\App\Model\Image $image) {
        $href = $image->getHref();
        $imageDir = IMAGE_ROOT;
        $file = dirname($imageDir) . $href;
        $db = $this->getDb();
        $db->beginTransaction();
        try {
            if (is_file($file)) {
                unlink($file);
            }
            $db->delete('franzl', ['id' => $image->getId()]);
            if (!is_file($file)) {
                $db->commit();
                return true;
            }
            $db->rollBack();
            return false;
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}