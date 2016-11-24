<?php
/**
 * This file is part of the PivotalTracker API component.
 *
 * @version 1.0
 * @copyright Copyright (c) 2012 Manuel Pichler
 * @license LGPL v3 license <http://www.gnu.org/licenses/lgpl>
 */

namespace PivotalTrackerV5;

/**
 * Simple Pivotal Tracker api client.
 *
 * This class is loosely based on the code from Joel Dare's PHP Pivotal Tracker
 * Class: https://github.com/codazoda/PHP-Pivotal-Tracker-Class
 */
class Client
{
    /**
     * Base url for the PivotalTracker service api.
     */
    const API_URL = 'https://www.pivotaltracker.com/services/v5';

    /**
     * Name of the context project.
     *
     * @var string
     */
    private $project;

    /**
     * Used client to perform rest operations.
     *
     * @var \PivotalTracker\Rest\Client
     */
    private $client;
    /**
     * 
     * @param string $apiKey  API Token provided by PivotalTracking
     * @param string $project Project ID
     */
    public function __construct( $apiKey, $project )
    {
        $this->client = new Rest\Client( self::API_URL );
        $this->client->addHeader( 'Content-type', 'application/json' );
        $this->client->addHeader( 'X-TrackerToken',  $apiKey );
        $this->project = $project;
    }

 
    /**
     * Adds a new story to PivotalTracker and returns the newly created story
     * object.
     *
     * @param array $story
     * @param string $name
     * @param string $description
     * @return object
     */
    public function addStory( array $story  )
    {
      
        return json_decode(
            $this->client->post(
                "/projects/{$this->project}/stories",
                json_encode( $story )
            )
        );
    }

    /**
     * Adds a new task with <b>$description</b> to the story identified by the
     * given <b>$storyId</b>.
     *
     * @param integer $storyId
     * @param string $description
     * @return \SimpleXMLElement
     */
    public function addTask( $storyId, $description )
    {
        return simplexml_load_string(
            $this->client->post(
                "/projects/{$this->project}/stories/$storyId/tasks",
                json_encode( array( 'description' => $description ) )
                
            )
        );
    }

    /**
     * Returns all memberships for a project.
     *
     * @param array $filter
     * @return object
     */
    public function getMemberships( $filter = null )
    {
        return json_decode(
            $this->client->get(
                "/projects/{$this->project}/memberships",
                $filter ? array( 'filter' => $filter ) : null
            )
        );
    }

    /**
     * Adds the given <b>$labels</b> to the story identified by <b>$story</b>
     * and returns the updated story instance.
     *
     * @param integer $storyId
     * @param array $labels
     * @return object
     */
    public function addLabels( $storyId, array $labels )
    {
        return json_decode(
            $this->client->put(
                "/projects/{$this->project}/stories/$storyId",
                json_encode(  $labels )
            )
        );
    }

    /**
     * Returns all stories for the context project.
     *
     * @param array $filter
     * @return object
     */
    public function getStories( $filter = null )
    {
        return json_decode(
            $this->client->get(
                "/projects/{$this->project}/stories",
                $filter ? array( 'filter' => $filter ) : null
            )
        );
    }

    /**
     * Returns a list of projects for the currently authenticated user.
     *
     * @return object
     */
    public function getProjects()
    {
        return json_decode(
            $this->client->get(
                "/projects"
            )
        );

    }

     
}
