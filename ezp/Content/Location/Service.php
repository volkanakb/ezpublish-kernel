<?php
/**
 * File containing the ezp\Content\Location\Service class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace ezp\Content\Location;
use ezp\Base\Exception,
    ezp\Base\Service as BaseService,
    ezp\Content\Location,
    ezp\Content\Proxy,
    ezp\Content\Section,
    ezp\Content\ContainerProperty,
    ezp\Base\Exception\NotFound,
    ezp\Base\Exception\InvalidArgumentType,
    ezp\Base\Exception\Logic,
    ezp\Persistence\Content\Location as LocationValue,
    ezp\Persistence\ValueObject,
    ezp\Persistence\Content\Location\CreateStruct;

/**
 * Location service, used for complex subtree operations
 */
class Service extends BaseService
{

    /**
     * Copies the subtree starting from $subtree as a new subtree of $targetLocation
     *
     * @param \ezp\Content\Location $subtree
     * @param \ezp\Content\Location $targetLocation
     *
     * @return \ezp\Content\Location The newly created subtree
     */
    public function copy( Location $subtree, Location $targetLocation )
    {
    }

    /**
     * Loads a location object from its $locationId
     * @param integer $locationId
     * @return \ezp\Content\Location
     * @throws \ezp\Base\Exception\NotFound if no location is available with $locationId
     */
    public function load( $locationId )
    {
        $locationVO = $this->handler->locationHandler()->load( $locationId );
        if ( !$locationVO instanceof LocationValue )
        {
            throw new NotFound( 'Location', $locationId );
        }

        return $this->buildDomainObject( $locationVO );
    }

    public function children( Location $location )
    {

    }

    /**
     * Creates the new $location in the content repository
     *
     * @param \ezp\Content\Location $location
     * @return \ezp\Content\Location the newly created Location
     * @throws \ezp\Base\Exception\Logic If a validation problem has been found for $content
     */
    public function create( Location $location )
    {
        if ( $location->parentId == 0 )
        {
            throw new Logic( 'Location', 'Parent location is not defined' );
        }

        $struct = new CreateStruct();
        foreach ( $location->properties() as $name => $value )
        {
            if ( property_exists( $struct, $name ) )
            {
                $struct->$name = $location->$name;
            }
        }

        $struct->invisible = ( $location->parent->invisible == true ) || ( $location->parent->hidden == true );
        $struct->contentId = $location->contentId;

        $vo = $this->handler->locationHandler()->createLocation( $struct, $location->parentId );
        $location->setState( array( 'properties' => $vo ) );

        // repo/storage stuff
        return $location;
    }

    /**
     * Updates $location in the content repository
     *
     * @param \ezp\Content\Location $location
     * @return \ezp\Content\Location the updated Location
     * @throws \ezp\Base\Exception\Validation If a validation problem has been found for $content
     */
    public function update( Location $location )
    {
        // repo/storage stuff
        return $location;
    }

    /**
     * Swaps the contents hold by the $location1 and $location2
     *
     * @param \ezp\Content\Location $location1
     * @param \ezp\Content\Location $location2
     * @return void
     * @throws \ezp\Base\Exception\Validation If a validation problem has been found
     */
    public function swap( Location $location1, Location $location2 )
    {

    }

    /**
     * Hides the $location and marks invisible all descendants of $location.
     *
     * @param \ezp\Content\Location $location
     * @return void
     * @throws \ezp\Base\Exception\Validation If a validation problem has been found
     */
    public function hide( Location $location )
    {
        // take care of :
        // 1. hiding $location
        // 2. making the whole subtree invisible
    }

    /**
     * Unhides the $location and marks visible all descendants of $locations
     * until a hidden location is found.
     *
     * @param \ezp\Content\Location $location
     * @return void
     * @throws \ezp\Base\Exception\Validation If a validation problem has been found;
     */
    public function unhide( Location $location )
    {
        // take care of :
        // 1. unhiding $location
        // 2. making the whole subtree visible (unless we found a hidden
        // location)
    }

    /**
     * Moves $location under $newParent and updates all descendants of
     * $location accordingly.
     *
     * @param \ezp\Content\Location $location
     * @param \ezp\Content\Location $newParent
     * @return void
     * @throws \ezp\Base\Exception\Validation If a validation problem has been found;
     */
    public function move( Location $location, Location $newParent )
    {
        // take care of :
        // 1. set parentId and path for $location
        // 2. changing path attribute to the subtree below $location
    }

    /**
     * Deletes the $locations and all descendants of $location.
     *
     * @param \ezp\Content\Location $location
     * @return void
     * @throws \ezp\Base\Exception\Validation If a validation problem has been found;
     */
    public function delete( Location $location )
    {
        // take care of:
        // 1. removing the current location
        // 2. removing the content addressed by the location if there's no more
        // location
        // 3. do the same operations on the subtree (recursive calls through
        // children ?)
        // note: this is different from Content::delete()
    }

    /**
     * Assigns $section to the contents hold by $startingPoint location and
     * all contents hold by descendants location of $startingPoint
     *
     * @param \ezp\Content\Location $startingPoint
     * @param Section $section
     * @return void
     * @throws \ezp\Base\Exception\Validation If a validation problem has been found;
     */
    public function assignSection( Location $startingPoint, Section $section )
    {
    }

    /**
     * Builds Location domain object from $vo ValueObject returned by Persistence API
     * @param \ezp\Persistence\Location $vo Location value object (extending \ezp\Persistence\ValueObject)
     *                                      returned by persistence
     * @return \ezp\content\Location
     * @throws \ezp\Base\Exception\InvalidArgumentType
     */
    protected function buildDomainObject( ValueObject $vo )
    {
        if ( !$vo instanceof LocationValue )
        {
            throw new InvalidArgumentType( 'Value object', 'ezp\\Persistence\\Content\\Location', $vo );
        }

        $location = new Location( new Proxy( $this->repository->getContentService(), $vo->contentId ) );
        $location->setState(
            array(
                'parent' => new Proxy( $this, $vo->parentId ),
                'properties' => $vo
            )
        );

        // Container property (default sorting)
        $containerProperty = new ContainerProperty;
        $location->containerProperties[] = $containerProperty->setState(
            array(
                'locationId' => $vo->id,
                'sortField' => $vo->sortField,
                'sortOrder' => $vo->sortOrder,
                'location' => $location
            )
        );

        return $location;
    }
}
