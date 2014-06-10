<?php

namespace M6Web\Bundle\AwsBundle\Tests\Units\Aws\Sts;

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
     * Tests assumeRole method
     */
    public function testAssumeRole()
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
     * Tests assumeRoleWithSAML method
     * 
     * @return void
     */
    public function testAssumeRoleWithSAML()
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
     * Tests assumeRoleWithWebIdentity method.
     * 
     * @return void
     */
    public function testAssumeRoleWithWebIdentity()
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
     * Tests getFederationToken method
     * 
     * @return void
     */
    public function testGetFederationToken()
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
     * Tests getSessionToken method.
     * 
     * @return void
     */
    public function testGetSessionToken()
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
