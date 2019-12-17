<?php
$boqx_id       = elgg_extract('cid', $vars);
$input_type    = elgg_extract('input_type', $vars, 'description');
$heading       = elgg_extract('heading', $vars, false);
$metadata_name = elgg_extract('metadata_name', $vars, 'description');
$guid          = elgg_extract('guid', $vars);
$value         = elgg_extract('value', $vars, false);
$placeholder   = $value ? elgg_format_element('span',['class'=>'tracker_markup'],elgg_format_element('p',[],$value)) : "Add a $metadata_name";
$cid           = quebx_new_id('c');
if($heading) 
    $show_heading = elgg_format_element('h4',[],$heading);

$description  = elgg_format_element('div',['id'=>$cid, 'data-boqx'=>$boqx_id, 'class'=>'Description___3oUx83yQ','data-aid'=>$heading],
                    elgg_format_element('input',['type'=>'hidden','name'=>"jot[$boqx_id][$input_type]",'data-focus-id'=>"Description--$cid",'value'=>$value]).
                    $show_heading.
                    elgg_format_element('div',['class'=>['DescriptionShow___3-QsNMNj','DescriptionShow__placeholder___1NuiicbF'],'data-cid'=>$cid, 'tabindex'=>'0','data-aid'=>'renderedDescription','data-focus-id'=>"DescriptionShow--$cid"],$placeholder).
                    elgg_format_element('div',['class'=>'DescriptionEdit___1FO6wKeX','data-cid'=>$cid],
                        elgg_format_element('div',['class'=>'markdownHelpContainer___32_mTqNL'],
                            elgg_format_element('div',[],
                                elgg_format_element('button',['class'=>'DescriptionEdit__tab___7lFo9PZA'],'Write').
                                elgg_format_element('button',['class'=>['DescriptionEdit__tab___7lFo9PZA','DescriptionEdit__tab--disabled___1x2-iUxr']],'Preview'))).
                        elgg_format_element('div',['class'=>'textContainer___2EcYJKlD', 'data-aid'=>'editor'],
                                       elgg_format_element('div',[],
                                            elgg_format_element('div',['class'=>'DescriptionEdit__write___207LwO1n'],
                                                 elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6'],
                                                      elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
                                                           elgg_format_element('textarea',['class'=>['AutosizeTextarea__textarea___1LL2IPEy','editor___1qKjhI5c','tracker_markup'],'aria-labeledby'=>"description$cid",'data-aid'=>'textarea', 'data-cid'=>$cid, 'data-focus-id'=>"DescriptionEdit--$cid", 'placeholder'=>"Add a $metadata_name"])
                                                      )
                                                 )
                                            ).
                                            elgg_format_element('div',['class'=>'DescriptionEdit__preview_____Tr424N'],
                                                 elgg_format_element('span',['class'=>'tracker_markup'],
                                                     elgg_format_element('p',[],
                                                         'Preview your '.
                                                         elgg_format_element('a',['href'=>'/help/markdown','target'=>'_blank','rel'=>'noopener','tabindex'=>'-1'],'Markdown formatted').
                                                         ' text here.'))
                                            )
                                       ).
                            elgg_format_element('div',['class'=>'controls___2K44hJCR'],
                                            elgg_format_element('div',['style'=>'white-space:nowrap;'],
                                                 elgg_format_element('button',['type'=>'button', 'class'=>['SMkCk__Button','QbMBD__Button--primary','_3olWk__Button--small','button__save___2XVnhUJI','_3Xvsn__Button--disabled'], 'data-aid'=>'save','data-cid'=>$cid],"Add $metadata_name").
                                                 elgg_format_element('button',['type'=>'button', 'class'=>['SMkCk__Button','ibMWB__Button--open','_3olWk__Button--small'],'data-aid'=>'cancel','data-cid'=>$cid],'Cancel')
                                            ).
                                elgg_format_element('div',['class'=>'Controls__text___B_l9ri3_',  'style'=>'display:none;'],
                                    elgg_format_element('button',['class'=>['IconButton___2y4Scyq6','IconButton--borderless___1t-CE8H2','IconButton--inverted___2OWhVJqP','IconButton--opaque___3am6FGGe'], 'data-aid'=>'AddEmoji','aria-label'=>"Add emoji to $metadata_name"],
                                        elgg_format_element('span',['class'=>'','title'=>"Add emoji to $metadata_name",'style'=>'background: url('.elgg_get_site_url().'mod/quebx/graphics/2b4b3f66-emoji-light.svg) center center no-repeat;'])
                                    ).
                                                 elgg_format_element('div',['class'=>['Dropdown','Dropdown--left','StoryTemplateDropdown___3ctobFmT']],
                                                      elgg_format_element('div',['class'=>'Dropdown__content','data-aid'=>'StoryTemplateDropdown'],
                                                           elgg_format_element('button',['type'=>'button','class'=>['SMkCk__Button','ibMWB__Button--open','StoryTemplate__button___6_DPoMAr','_1m_u1__Button--short']],
                                                                elgg_format_element('img',['class'=>'_3iG1d__IconButton__icon','title'=>'Select story template','src'=>elgg_get_site_url().'mod/quebx/graphics/b202db4f-story-templates.svg'])
                                                           )
                                                      )
                                                 )
                                            )
                                       )
                                  )
                            )
                       );
echo $description;