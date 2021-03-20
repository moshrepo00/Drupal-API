<?php

namespace Drupal\api_user\Plugin\rest\resource;

use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "user_pofile_resource",
 *   label = @Translation("User profile resource"),
 *   uri_paths = {
 *     "canonical" = "/getuserprofile"
 *   }
 * )
 */
class UserProfileResource extends ResourceBase {

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
     * Responds to GET requests.
     *
     * @param string $payload
     *
     * @return \Drupal\rest\ResourceResponse
     *   The HTTP response object.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *   Throws exception expected.
     */
    public function get($payload) {

      $currentUserId = \Drupal::currentUser()->id();

      if (empty($currentUserId)) {
        return new ResourceResponse('Unauthorized', 403);
      }

      $user = User::load($currentUserId);


      $response = [
        'name' => $user->name->value,
        'email' => $user->mail->value
      ];
      return new ResourceResponse($response, 200);
    }

}
