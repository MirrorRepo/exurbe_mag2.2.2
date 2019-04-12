<?php
/* 
 * @package Credevlabz/Testimonials
 * @category Api
 * @author Aman Srivastava <http://amansrivastava.in>
 *
 */

namespace Magenest\ImageGallery\Api\Data;


interface ImageInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const TESTIMONIAL_ID = 'image_id';
    const NAME         = 'group_name';
    const IMAGE       = 'image';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Get email
     *
     * @return string|null
     */
    public function getImage();

        /**
     * Set ID
     *
     * @param int $id
     * @return \Credevlabz\Testimonials\Api\Data\TestimonialInterface
     */
    public function setId($id);

    /**
     * Set name
     *
     * @param string $name
     * @return \Credevlabz\Testimonials\Api\Data\TestimonialInterface
     */
    public function setName($name);

    /**
     * Set email
     *
     * @param string $email
     * @return \Credevlabz\Testimonials\Api\Data\TestimonialInterface
     */
    public function setImage($email);
}