<?php

namespace M6Web\Bundle\AwsBundle\Tests\Units\Aws\Sts;

require_once __DIR__ . '/../../../../../../../../vendor/autoload.php';

use atoum;
use M6Web\Bundle\AwsBundle\Aws\Sts\Client as Base;

/**
 * Sts Client
 */
class Client extends atoum
{
    /**
     * Tests createCredentials method.
     * 
     * @return void
     */
    public function testCreateCredentials()
    {
        $this
            ->if($stsClient = $this->createStsClientMock())
            ->and($client = new Base($stsClient))
            ->and($stsClient->getMockController()->createCredentials = null)
            ->and($model = new \mock\Guzzle\Service\Resource\Model())
            ->and($client->createCredentials($model))
            ->then
                ->mock($stsClient)
                    ->call('createCredentials')
                        ->withArguments($model)
                        ->once();
    }

    /**
     * Tests assumeRole method with required parameters only.
     */
    public function testAssumeRoleRequiredParams()
    {
        $this
            ->if($stsClient = $this->createStsClientMock())
            ->and($client = new Base($stsClient))
            ->and($stsClient->getMockController()->assumeRole = null)
            ->and($client = new Base($stsClient))
            ->and(
                $client->assumeRole(
                    $roleArn         = uniqid(),
                    $roleSessionName = uniqid()
                )
            )
            ->then
                ->mock($stsClient)
                    ->call('assumeRole')
                        ->withArguments([
                            'RoleArn'         => $roleArn,
                            'RoleSessionName' => $roleSessionName,
                            'DurationSeconds' => 3600
                        ])
                        ->once();
    }

    /**
     * Tests assumeRole method with all parameters.
     */
    public function testAssumeRoleAllParams()
    {
        $this
            ->if($stsClient = $this->createStsClientMock())
            ->and($client = new Base($stsClient))
            ->and($stsClient->getMockController()->assumeRole = null)
            ->and($client = new Base($stsClient))
            ->and(
                $client->assumeRole(
                    $roleArn         = uniqid(),
                    $roleSessionName = uniqid(),
                    $policy          = uniqid(),
                    $durationSeconds = rand(),
                    $externalId      = uniqid(),
                    $serialNumber    = uniqid(),
                    $tokenCode       = uniqid()
                )
            )
            ->then
                ->mock($stsClient)
                    ->call('assumeRole')
                        ->withArguments([
                            'RoleArn'         => $roleArn,
                            'RoleSessionName' => $roleSessionName,
                            'Policy'          => $policy,
                            'DurationSeconds' => $durationSeconds,
                            'ExternalId'      => $externalId,
                            'SerialNumber'    => $serialNumber,
                            'TokenCode'       => $tokenCode
                        ])
                        ->once();
    }

    /**
     * Tests assumeRoleWithSAML method with required parameters only.
     * 
     * @return void
     */
    public function testAssumeRoleWithSAMLRequiredParams()
    {
        $this
            ->if($stsClient = $this->createStsClientMock())
            ->and($client = new Base($stsClient))
            ->and($stsClient->getMockController()->assumeRoleWithSAML = null)
            ->and($client = new Base($stsClient))
            ->and(
                $client->assumeRoleWithSAML(
                    $roleArn       = uniqid(),
                    $principalArn  = uniqid(),
                    $SAMLAssertion = uniqid()
                )
            )
            ->then
                ->mock($stsClient)
                    ->call('assumeRoleWithSAML')
                        ->withArguments([
                            'RoleArn'         => $roleArn,
                            'PrincipalArn'    => $principalArn,
                            'SAMLAssertion'   => $SAMLAssertion,
                            'DurationSeconds' => 3600
                        ])
                        ->once();
    }

    /**
     * Tests assumeRoleWithSAML method with all parameters.
     * 
     * @return void
     */
    public function testAssumeRoleWithSAMLAllParams()
    {
        $this
            ->if($stsClient = $this->createStsClientMock())
            ->and($client = new Base($stsClient))
            ->and($stsClient->getMockController()->assumeRoleWithSAML = null)
            ->and($client = new Base($stsClient))
            ->and(
                $client->assumeRoleWithSAML(
                    $roleArn         = uniqid(),
                    $principalArn    = uniqid(),
                    $SAMLAssertion   = uniqid(),
                    $policy          = uniqid(),
                    $durationSeconds = rand()
                )
            )
            ->then
                ->mock($stsClient)
                    ->call('assumeRoleWithSAML')
                        ->withArguments([
                            'RoleArn'         => $roleArn,
                            'PrincipalArn'    => $principalArn,
                            'SAMLAssertion'   => $SAMLAssertion,
                            'Policy'          => $policy,
                            'DurationSeconds' => $durationSeconds,
                        ])
                        ->once();
    }

    /**
     * Tests assumeRoleWithWebIdentity method with required parameters only.
     * 
     * @return void
     */
    public function testAssumeRoleWithWebIdentityRequiredParams()
    {
        $this
            ->if($stsClient = $this->createStsClientMock())
            ->and($client = new Base($stsClient))
            ->and($stsClient->getMockController()->assumeRoleWithWebIdentity = null)
            ->and($client = new Base($stsClient))
            ->and(
                $client->assumeRoleWithWebIdentity(
                    $roleArn          = uniqid(),
                    $roleSessionName  = uniqid(),
                    $webIdentityToken = uniqid()
                )
            )
            ->then
                ->mock($stsClient)
                    ->call('assumeRoleWithWebIdentity')
                        ->withArguments([
                            'RoleArn'          => $roleArn,
                            'RoleSessionName'  => $roleSessionName,
                            'WebIdentityToken' => $webIdentityToken,
                            'DurationSeconds'  => 3600
                        ])
                        ->once();
    }

    /**
     * Tests assumeRoleWithWebIdentity method with all parameters.
     * 
     * @return void
     */
    public function testAssumeRoleWithWebIdentityAllParams()
    {
        $this
            ->if($stsClient = $this->createStsClientMock())
            ->and($client = new Base($stsClient))
            ->and($stsClient->getMockController()->assumeRoleWithWebIdentity = null)
            ->and($client = new Base($stsClient))
            ->and(
                $client->assumeRoleWithWebIdentity(
                    $roleArn          = uniqid(),
                    $roleSessionName  = uniqid(),
                    $webIdentityToken = uniqid(),
                    $providerId       = uniqid(),
                    $policy           = uniqid(),
                    $durationSeconds  = rand()
                )
            )
            ->then
                ->mock($stsClient)
                    ->call('assumeRoleWithWebIdentity')
                        ->withArguments([
                            'RoleArn'          => $roleArn,
                            'RoleSessionName'  => $roleSessionName,
                            'WebIdentityToken' => $webIdentityToken,
                            'ProviderId'       => $providerId,
                            'Policy'           => $policy,
                            'DurationSeconds'  => $durationSeconds
                        ])
                        ->once();
    }

    /**
     * Tests decodeAuthorizationMessage method.
     * 
     * @return void
     */
    public function testDecodeAuthorizationMessage()
    {
        $this
            ->if($stsClient = $this->createStsClientMock())
            ->and($client = new Base($stsClient))
            ->and($stsClient->getMockController()->decodeAuthorizationMessage = null)
            ->and($client = new Base($stsClient))
            ->and($client->decodeAuthorizationMessage($encodedMessage = uniqid()))
            ->then
                ->mock($stsClient)
                    ->call('decodeAuthorizationMessage')
                        ->withArguments([
                            'EncodedMessage' => $encodedMessage
                        ])
                        ->once();
    }

    /**
     * Tests getFederationToken method with required parameters only.
     * 
     * @return void
     */
    public function testGetFederationTokenRequiredParams()
    {
        $this
            ->if($stsClient = $this->createStsClientMock())
            ->and($client = new Base($stsClient))
            ->and($stsClient->getMockController()->getFederationToken = null)
            ->and($client = new Base($stsClient))
            ->and(
                $client->getFederationToken(
                    $name = uniqid()
                )
            )
            ->then
                ->mock($stsClient)
                    ->call('getFederationToken')
                        ->withArguments([
                            'Name'            => $name,
                            'DurationSeconds' => 43200
                        ])
                        ->once();
    }

    /**
     * Tests getFederationToken method with all parameters.
     * 
     * @return void
     */
    public function testGetFederationTokenAllParams()
    {
        $this
            ->if($stsClient = $this->createStsClientMock())
            ->and($client = new Base($stsClient))
            ->and($stsClient->getMockController()->getFederationToken = null)
            ->and($client = new Base($stsClient))
            ->and(
                $client->getFederationToken(
                    $name            = uniqid(),
                    $policy          = uniqid(),
                    $durationSeconds = rand()
                )
            )
            ->then
                ->mock($stsClient)
                    ->call('getFederationToken')
                        ->withArguments([
                            'Name'            => $name,
                            'Policy'          => $policy,
                            'DurationSeconds' => $durationSeconds
                        ])
                        ->once();
    }

    /**
     * Tests getSessionToken method with no parameters.
     * 
     * @return void
     */
    public function testGetSessionTokenNoParams()
    {
        $this
            ->if($stsClient = $this->createStsClientMock())
            ->and($client = new Base($stsClient))
            ->and($stsClient->getMockController()->getSessionToken = null)
            ->and($client = new Base($stsClient))
            ->and($client->getSessionToken())
            ->then
                ->mock($stsClient)
                    ->call('getSessionToken')
                        ->withArguments([
                            'DurationSeconds' => 43200
                        ])
                        ->once();
    }

    /**
     * Tests getSessionToken method with all parameters.
     * 
     * @return void
     */
    public function testGetSessionTokenAllParams()
    {
        $this
            ->if($stsClient = $this->createStsClientMock())
            ->and($client = new Base($stsClient))
            ->and($stsClient->getMockController()->getSessionToken = null)
            ->and($client = new Base($stsClient))
            ->and($client->getSessionToken(
                $durationSeconds = rand(),
                $serialNumber    = uniqid(),
                $tokenCode       = uniqid()
            ))
            ->then
                ->mock($stsClient)
                    ->call('getSessionToken')
                        ->withArguments([
                            'DurationSeconds' => $durationSeconds,
                            'SerialNumber'    => $serialNumber,
                            'TokenCode'       => $tokenCode
                        ])
                        ->once();
    }

    protected function createStsClientMock()
    {
        $credentials = new \mock\Aws\Common\Credentials\CredentialsInterface();
        $signature   = new \mock\Aws\Common\Signature\SignatureInterface();
        $collection  = new \mock\Guzzle\Common\Collection();

        return new \mock\Aws\Sts\StsClient($credentials, $signature, $collection);
    }
}
