<?php
/**
 * Created by PhpStorm.
 * User: kasi
 * Date: 29.01.2019
 * Time: 14:20
 */

namespace App\Model;


use App\Lib\JsonLib;

class Image implements \JsonSerializable {
    protected $id;
    protected $title;
    protected $info;
    protected $href;
    protected $created;
    protected $tag;
    protected $latitude;
    protected $longitude;

    /**
     * @return mixed
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude): void {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude): void {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getTag() {
        return $this->tag;
    }

    /**
     * @param mixed $tag
     */
    public function setTag($tag): void {
        $this->tag = $tag;
    }

    /**
     * Image constructor.
     * @param array $data
     */
    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? '';
        $this->title = $data['title'] ?? '';
        $this->info = $data['info'] ?? '';
        $this->href = $data['href'] ?? '';
        $this->tag = $data['tag'] ?? '';
        $this->latitude = $data['latitude'] ?? '';
        $this->longitude = $data['longitude'] ?? '';
        $this->created = $data['created'] ?? '';
    }
    public function jsonSerialize() {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'info'=>$this->info,
            'href'=>$this->href,
            'tag'=>$this->tag,
            'latitude'=>$this->latitude,
            'longitude'=>$this->longitude,
            'created'=>$this->created,
        ];
    }

    /**
     * @param $title
     */
    public function setTitle($title){
        $this->title = $title;
    }
    /**
     * @param $info
     */
    public function setInfo($info){
        $this->info = $info;
    }
    /**
     * @return mixed|string
     */
    public function getHref() {
        return $this->href;
    }

    /**
     * @return mixed|string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @return mixed|string
     */
    public function getInfo() {
        return $this->info;
    }

    /**
     * @return mixed
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @return mixed|string
     */
    public function getId() {
        return $this->id;
    }

}