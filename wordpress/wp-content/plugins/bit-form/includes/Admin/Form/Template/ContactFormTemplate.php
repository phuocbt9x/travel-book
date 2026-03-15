<?php

namespace BitCode\BitForm\Admin\Form\Template;

final class ContactFormTemplate extends TemplateBase
{
  protected $title = 'Contact Form';
  protected $description = 'Desc';
  protected $status = 'free';
  protected $thumbnail = '';
  protected $category = 'general';

  protected function layout($newFormId)
  {
    $layoutData = sprintf(
      '{"lg":[{"w":30,"h":40,"x":0,"y":0,"i":"bf%1$s-1","minH":40,"maxH":40,"moved":false,"static":false},{"w":30,"h":40,"x":30,"y":0,"i":"bf%1$s-2","minH":40,"maxH":40,"moved":false,"static":false},{"w":60,"h":40,"x":0,"y":40,"i":"bf%1$s-3","minH":40,"maxH":40,"moved":false,"static":false},{"w":60,"h":40,"x":0,"y":80,"i":"bf%1$s-4","minH":40,"maxH":40,"moved":false,"static":false},{"w":60,"h":60,"x":0,"y":120,"i":"bf%1$s-5","moved":false,"static":false},{"w":60,"h":40,"x":0,"y":160,"i":"bf%1$s-6","moved":false,"static":false}],"md":[{"i":"bf%1$s-1","x":0,"y":0,"w":40,"h":40,"minH":40,"maxH":40},{"i":"bf%1$s-2","x":0,"y":40,"w":4,"h":40,"minH":40,"maxH":40},{"i":"bf%1$s-3","x":0,"y":80,"w":40,"h":40,"minH":40,"maxH":40},{"i":"bf%1$s-4","x":0,"y":120,"w":40,"h":40,"minH":40,"maxH":40},{"i":"bf%1$s-5","x":0,"y":160,"w":40,"h":60},{"i":"bf%1$s-6","x":0,"y":200,"w":40,"h":20}],"sm":[{"i":"bf%1$s-1","x":0,"y":0,"w":20,"h":40,"minH":40,"maxH":40},{"i":"bf%1$s-2","x":0,"y":40,"w":20,"h":40,"minH":40,"maxH":40},{"i":"bf%1$s-3","x":0,"y":80,"w":20,"h":40,"minH":40,"maxH":40},{"i":"bf%1$s-4","x":0,"y":120,"w":20,"h":40,"minH":40,"maxH":40},{"i":"bf%1$s-5","x":0,"y":160,"w":20,"h":60},{"i":"bf%1$s-6","x":0,"y":200,"w":20,"h":40}]}
',
      $newFormId
    );
    return $layoutData;
  }

  protected function fields($newFormId)
  {
    $fieldData = sprintf(
      '{"bf%1$s-1":{"typ":"text","lbl":"First Name","ph":"Enter Your First Name","valid":{},"err":{}},"bf%1$s-2":{"typ":"text","lbl":"Last Name","ph":"Enter Your Last Name","valid":{},"err":{}},"bf%1$s-3":{"typ":"email","lbl":"Email","ph":"example@mail.com","pattern":"^[^\\$_bf_\\$s@]+@[^\\$_bf_\\$s@]+\\$_bf_\\$.[^\\$_bf_\\$s@]+$","valid":{},"err":{"invalid":{"dflt":"Email is invalid","show":true}}},"bf%1$s-4":{"typ":"text","lbl":"Subject","ph":"Contact Reason","valid":{},"err":{}},"bf%1$s-5":{"typ":"textarea","lbl":"Message","ph":"Placeholder Text...","valid":{},"err":{}},"bf%1$s-6":{"typ":"button","btnTyp":"submit","align":"right","btnSiz":"md","txt":"Submit","valid":{}}}
',
      $newFormId
    );
    return $fieldData;
  }
}
