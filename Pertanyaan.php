<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    protected $table = 'pertanyaan';
    public $timestamps = false;

    protected $fillable = [
        'pertanyaan', 'order', 'parent_id',
      ];

    public function buildMenu($menu, $parentid = 0)
    {
        $result = null;
        foreach ($menu as $item) {
            if ($item->parent_id == $parentid) {
                $result .= "<li class='dd-item nested-list-item' data-order='{$item->order}' data-id='{$item->id}'>
	<div class='dd-handle nested-list-handle'>
          <i class='fas fa-arrows-alt'></i>
	</div>
	<div class='nested-list-content'>{$item->pertanyaan}
    <div class='float-right'>
    <a  class='button-plus btn btn-default btn-xs pull-right' href='/menustop/{$item->id}'> <i class='fa fa-plus' aria-hidden='true'></i></a> 
    <a  class='button-edit btn btn-default btn-xs pull-right' href='/menustop/{$item->id}'> <i class='fa fa-pencil' aria-hidden='true'></i></a> 
      <a href='#deleteModal' class='button-delete btn btn-default btn-xs pull-right' rel='{$item->id}' data-toggle='modal'> <i class='fa fa-times-circle-o' aria-hidden='true'></i></a>
      
	  </div>
  </div>".$this->buildMenu($menu, $item->id).'</li>';
            }
        }

        return $result ? "\n<ol class=\"dd-list\">\n$result</ol>\n" : null;
    }

    // Getter for the HTML menu builder
    public function getHTML($items)
    {
        return $this->buildMenu($items);
    }
}
