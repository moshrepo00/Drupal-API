<?php

namespace Drupal\api_user\Plugin\rest\resource;

use Drupal\file\Entity\File;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\StreamWrapper\PublicStream;


/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "user_update_profile_resource",
 *   label = @Translation("User update profile resource"),
 *   uri_paths = {
 *     "create" = "/updateuserprofile"
 *   }
 * )
 */
class UserUpdateProfileResource extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->logger = $container->get('logger.factory')->get('api_user');
    $instance->currentUser = $container->get('current_user');
    return $instance;
  }

    /**
     * Responds to POST requests.
     *
     * @param string $payload
     *
     * @return \Drupal\rest\ModifiedResourceResponse
     *   The HTTP response object.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *   Throws exception expected.
     */
    public function post($payload) {



      $ifp = fopen(PublicStream::basePath() . '/' . $payload['fileName'],"wb");
      fwrite( $ifp, base64_decode( $payload[ 'base64string' ] ) );
      fclose($ifp);

      $destination = PublicStream::basePath() . '/' . $payload['fileName'];

      $filesystem = \Drupal::service('file_system');
      $file = \Drupal\file\Entity\File::create();
      $file->setFileUri($destination);
      $file->setOwnerId(\Drupal::currentUser()->id());
      $file->setMimeType($payload['fileType']);
      $file->setFileName($filesystem->basename($destination));
      $file->setPermanent();
      $file->save();
      $result = ['id' => $file->id()];
      $currentUserId = \Drupal::currentUser()->id();
      $user = \Drupal\user\Entity\User::load($currentUserId);
      $user->set('user_picture', [
        'target_id' => $file->id(),
        'title' => $user->name->value,
        'alt' => $user->name->value
      ]);
      $user->save();
      return new ModifiedResourceResponse($result, 200);
    }

}
