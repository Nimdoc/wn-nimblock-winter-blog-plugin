<?php namespace Nimdoc\NimblockWinterBlog;
/*********************************************************************
* Copyright (c) 2024 Tom Busby
*
* This program and the accompanying materials are made
* available under the terms of the Eclipse Public License 2.0
* which is available at https://www.eclipse.org/legal/epl-2.0/
*
* SPDX-License-Identifier: EPL-2.0
**********************************************************************/

use System\Classes\PluginBase;
use Event;
use Winter\Blog\Models\Post;

use Nimdoc\NimblockWinterBlog\Classes\Event\ExtendWinterBlog;
use Nimdoc\NimblockEditor\Classes\ConvertToHtml;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'NimblockEditor for Winter Blog',
            'description' => 'Provides the Nimblock Editor for the Winter Blog plugin',
            'author' => 'Tom Busby',
            'icon' => 'icon-pencil-square-o'
        ];
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array|void
     */
    public function boot()
    {
        Event::subscribe(ExtendWinterBlog::class);

        // Extend the Post model
        Post::extend(function($model) {
            $model->addDynamicMethod('getContentHtmlAttribute', function() use ($model) {
                $convertToHtml = new ConvertToHtml();
                $html = $convertToHtml->convertJsonToHtml($model->content);
                return $html;
            });
        });
    }
}
