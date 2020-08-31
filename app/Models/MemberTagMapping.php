<?php

namespace App;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $member_id
 * @property int $tag_id
 */
class MemberTagMapping extends BaseModel
{
    protected $table = 'member_tag_mapping';

    protected $primaryKey = ['member_id', 'tag_id'];

    public $timestamps = false;

    public $incrementing = false;

    public function tag()
    {
        return $this->hasOne(Tag::class, 'id', 'tag_id');
    }
    
    /**
    * Set the keys for a save update query.
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @return \Illuminate\Database\Eloquent\Builder
    */
   protected function setKeysForSaveQuery(Builder $query)
   {
       $keys = $this->getKeyName();
       if(!is_array($keys)){
           return parent::setKeysForSaveQuery($query);
       }
   
       foreach($keys as $keyName){
           $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
       }
   
       return $query;
   }
   
   /**
    * Get the primary key value for a save query.
    *
    * @param mixed $keyName
    * @return mixed
    */
   protected function getKeyForSaveQuery($keyName = null)
   {
       if(is_null($keyName)){
           $keyName = $this->getKeyName();
       }
   
       if (isset($this->original[$keyName])) {
           return $this->original[$keyName];
       }
   
       return $this->getAttribute($keyName);
   }
}
