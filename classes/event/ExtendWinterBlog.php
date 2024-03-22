<?php namespace Nimdoc\NimblockWinterBlog\Classes\Event;
/*********************************************************************
* Copyright (c) 2024 Tom Busby
*
* This program and the accompanying materials are made
* available under the terms of the Eclipse Public License 2.0
* which is available at https://www.eclipse.org/legal/epl-2.0/
*
* SPDX-License-Identifier: EPL-2.0
**********************************************************************/

use System\Classes\PluginManager;

class ExtendWinterBlog
{
    public function subscribe($event)
    {
        if (PluginManager::instance()->hasPlugin('Winter.Blog')) {
            $event->listen('backend.form.extendFields', function ($widget) {
                if ($widget->getController() instanceof \Winter\Blog\Controllers\Posts && $widget->model instanceof \Winter\Blog\Models\Post) {
                    $fieldType = 'editorjs';
                    $fieldWidgetPath = 'Nimdoc\NimblockEditor\FormWidgets\EditorJS';

                    // Finding content field and changing it's type regardless whatever it already is.
                    foreach ($widget->getFields() as $field) {
                        if ($field->fieldName === 'content') {
                            $field->config['type'] = $fieldType;
                            $field->config['widget'] = $fieldWidgetPath;
                            $field->config['stretch'] = true;
                        }
                    }
                }
            });
            
            \Winter\Blog\Models\Post::extend(function ($model) {
                $model->implement[] = 'Nimdoc.NimblockEditor.Behaviors.ConvertToHtml';
                $model->addDynamicMethod('getContentRenderAttribute', function () use ($model) {
                    return $model->convertJsonToHtml($model->getAttribute('content'));
                });
            });
        }
    }
}