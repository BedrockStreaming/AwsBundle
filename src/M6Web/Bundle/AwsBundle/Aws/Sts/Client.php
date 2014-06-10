<?php

namespace M6Web\Bundle\AwsBundle\Aws\Sts;

use Aws\Sts\StsClient;

use Aws\Common\Credentials\Credentials;
use Guzzle\Service\Resource\Model;

/**
 * Sts Client
 */
class Client
{
    /**
     * @var StsClient
     */
    protected $client;

    /**
     * __construct
     *
     * @param StsClient $client Aws Sts Client
     */
    public function __construct(StsClient $client)
    {
        $this->client = $client;
    }

    /**
     * Direct access to the Sts client
     *
     * @return StsClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Creates a credentials object from the credential data return by an STS operation.
     * 
     * @param Model $result The result of an STS operation
     * 
     * @return Credentials
     *
     * @throws Aws\Common\Exception\InvalidArgumentException If the result does not contain credential data
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.Sts.StsClient.html#_createCredentials
     */
    public function createCredentials(Model $result)
    {
        return $this->client->createCredentials($result);
    }


    /**
     * Returns a set of temporary security credentials 
     * (consisting of an access key ID, a secret access key, and a security token) 
     * that you can use to access AWS resources that you might not normally have access to. 
     * Typically, you use AssumeRole for cross-account access or federation.
     * 
     * @param string  $roleArn         The Amazon Resource Name (ARN) of the role that the caller is assuming.
     * @param string  $roleSessionName An identifier for the assumed role session. The session name is included as part of the AssumedRoleUser.
     * @param string  $policy          An IAM policy in JSON format.
     * @param integer $durationSeconds The duration, in seconds, of the role session. The value can range from 900 seconds (15 minutes) to 3600 seconds (1 hour).
     * @param string  $externalId      A unique identifier that is used by third parties to assume a role in their customers' accounts.
     * @param string  $serialNumber    The identification number of the MFA device that is associated with the user who is making the AssumeRole call.
     * @param string  $tokenCode       The value provided by the MFA device, if the trust policy of the role being assumed requires MFA.
     * 
     * @return Guzzle\Service\Resource\Model
     *
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.Sts.StsClient.html#_assumeRole
     */
    public function assumeRole(
        $roleArn,
        $roleSessionName,
        $policy          = null,
        $durationSeconds = 3600,
        $externalId      = null,
        $serialNumber    = null,
        $tokenCode       = null
    )
    {
        $args = [
            'RoleArn'         => $roleArn,
            'RoleSessionName' => $roleSessionName,
            'DurationSeconds' => $durationSeconds
        ];

        if ($policy !== null) {
            $args['Policy'] = $policy;
        }

        if ($externalId !== null) {
            $args['ExternalId'] = $externalId;
        }

        if ($serialNumber !== null) {
            $args['SerialNumber'] = $serialNumber;
        }

        if ($tokenCode !== null) {
            $args['TokenCode'] = $tokenCode;
        }

        return $this->client->assumeRole($args);
    }

    /**
     * Returns a set of temporary security credentials for users who have been 
     * authenticated via a SAML authentication response. 
     * This operation provides a mechanism for tying an enterprise identity 
     * store or directory to role-based AWS access without user-specific 
     * credentials or configuration.
     * 
     * @param string  $roleArn         The Amazon Resource Name (ARN) of the role that the caller is assuming.
     * @param string  $principalArn    The Amazon Resource Name (ARN) of the SAML provider in IAM that describes the IdP.
     * @param string  $SAMLAssertion   The base-64 encoded SAML authentication response provided by the IdP.
     * @param string  $policy          An IAM policy in JSON format.
     * @param integer $durationSeconds The duration, in seconds, of the role session.
     * 
     * @return Guzzle\Service\Resource\Model
     *
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.Sts.StsClient.html#_assumeRoleWithSAML
     */
    public function assumeRoleWithSAML($roleArn, $principalArn, $SAMLAssertion, $policy = null, $durationSeconds = 3600)
    {
        $args = [
            'RoleArn'         => $roleArn,
            'PrincipalArn'    => $principalArn,
            'SAMLAssertion'   => $SAMLAssertion,
            'DurationSeconds' => $durationSeconds
        ];

        if ($policy !== null) {
            $args['Policy'] = $policy;
        }

        return $this->client->assumeRoleWithSAML($args);
    }

    /**
     * Returns a set of temporary security credentials for users who have been authenticated in a mobile 
     * or web application with a web identity provider, such as Login with Amazon, Facebook, or Google.
     *
     * @param string  $roleArn          The Amazon Resource Name (ARN) of the role that the caller is assuming.
     * @param string  $roleSessionName  An identifier for the assumed role session.
     * @param string  $webIdentityToken The OAuth 2.0 access token or OpenID Connect ID token that is provided by the identity provider.
     * @param string  $providerId       The fully-qualified host component of the domain name of the identity provider.
     * @param string  $policy           An IAM policy in JSON format.
     * @param integer $durationSeconds  The duration, in seconds, of the role session.
     * 
     * @return Guzzle\Service\Resource\Model
     *
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.Sts.StsClient.html#_assumeRoleWithWebIdentity
     */
    public function assumeRoleWithWebIdentity(
        $roleArn,
        $roleSessionName,
        $webIdentityToken,
        $providerId = null,
        $policy = null,
        $durationSeconds = 3600
    )
    {
        $args = [
            'RoleArn'          => $roleArn,
            'RoleSessionName'  => $roleSessionName,
            'WebIdentityToken' => $webIdentityToken,
            'DurationSeconds'  => $durationSeconds
        ];

        if ($providerId !== null) {
            $args['ProviderId'] = $providerId;
        }

        if ($policy !== null) {
            $args['Policy'] = $policy;
        }

        return $this->client->assumeRoleWithWebIdentity($args);
    }

    /**
     * Decodes additional information about the authorization status of a request
     * from an encoded message returned in response to an AWS request.
     * 
     * @param string $encodedMessage The encoded message that was returned with the response.
     * 
     * @return Guzzle\Service\Resource\Model
     *
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.Sts.StsClient.html#_decodeAuthorizationMessage
     */
    public function decodeAuthorizationMessage($encodedMessage)
    {
        return $this->client->decodeAuthorizationMessage(['EncodedMessage' => $encodedMessage]);
    }

    /**
     * Returns a set of temporary security credentials (consisting of an access key ID, a secret access key, 
     * and a security token) for a federated user.
     * 
     * @param string  $name            The name of the federated user.
     * @param string  $policy          An IAM policy in JSON format that is passed with the GetFederationToken call and evaluated along with the policy or policies that are attached to the IAM user whose credentials are used to call GetFederationToken.
     * @param integer $durationSeconds The duration, in seconds, that the session should last.
     * 
     * @return Guzzle\Service\Resource\Model
     *
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.Sts.StsClient.html#_getFederationToken
     */
    public function getFederationToken($name, $policy = null, $durationSeconds = 43200)
    {
        $args = [
            'Name'            => $name,
            'DurationSeconds' => $durationSeconds
        ];

        if ($policy !== null) {
            $args['Policy'] = $policy;
        }

        return $this->client->getFederationToken($args);
    }

    /**
     * Returns a set of temporary credentials for an AWS account or IAM user.
     * 
     * @param integer $durationSeconds The duration, in seconds, that the credentials should remain valid.
     * @param string  $serialNumber    The identification number of the MFA device that is associated with the IAM user who is making the GetSessionToken call.
     * @param string  $tokenCode       The value provided by the MFA device, if MFA is required.
     * 
     * @return Guzzle\Service\Resource\Model
     *
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.Sts.StsClient.html#_getSessionToken
     */
    public function getSessionToken($durationSeconds = 43200, $serialNumber = null, $tokenCode = null)
    {
        $args = [
            'DurationSeconds' => $durationSeconds
        ];

        if ($serialNumber !== null) {
            $args['SerialNumber'] = $serialNumber;
        }

        if ($tokenCode !== null) {
            $args['TokenCode'] = $tokenCode;
        }

        return $this->client->getSessionToken($args);
    }
}
