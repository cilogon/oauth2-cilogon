<?php

namespace CILogon\OAuth2\Client\Test\Provider;

use Mockery as m;

class CILogonTest extends \PHPUnit_Framework_TestCase
{
    protected $provider;

    protected function setUp()
    {
        $this->provider = new \CILogon\OAuth2\Client\Provider\CILogon([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none',
        ]);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    public function testAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertArrayHasKey('scope', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('approval_prompt', $query);

        $this->assertAttributeNotEmpty('state', $this->provider);
    }

    public function testScopes()
    {
        $options = ['scope' => [uniqid()]];

        $url = $this->provider->getAuthorizationUrl($options);

        $this->assertContains(urlencode(implode(' ', $options['scope'])), $url);
    }

    public function testDefaultScopes()
    {
        $url = $this->provider->getAuthorizationUrl();

        $this->assertContains('openid', $url);
    }

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        $this->assertEquals('/authorize', $uri['path']);
    }

    public function testBaseAccessTokenUrl()
    {
        $url = $this->provider->getBaseAccessTokenUrl([]);
        $uri = parse_url($url);
        $this->assertEquals('/oauth2/token', $uri['path']);
    }

    public function testResourceOwnerDetailsUrl()
    {
        $token = m::mock('League\OAuth2\Client\Token\AccessToken');
        $url = $this->provider->getResourceOwnerDetailsUrl($token);
        $uri = parse_url($url);
        $this->assertEquals('/oauth2/userinfo', $uri['path']);
    }

    public function testGetAccessToken()
    {
        $response = m::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(
            '{"access_token":"mock_access_token"' .
            ',"token_type":"bearer"' .
            ',"refresh_token":"mock_refresh_token"}'
        );
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $response->shouldReceive('getStatusCode')->andReturn(200);

        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => 'mock_authorization_code'
        ]);

        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertNull($token->getExpires());
        $this->assertEquals('mock_refresh_token', $token->getRefreshToken());
        $this->assertNull($token->getResourceOwnerId());
    }

    public function testUserData()
    {
        $id = uniqid();
        $name = uniqid();
        $given_name = uniqid();
        $family_name = uniqid();
        $email = uniqid();
        $eppn = uniqid();
        $eptid = uniqid();
        $idp = uniqid();
        $idpname = uniqid();
        $ou = uniqid();
        $affiliation = uniqid();
        $acr = uniqid();
        $subject_id = uniqid();
        $pairwise_id = uniqid();
        $voPersonExternalId = uniqid();
        $uid = uniqid();
        $uidNumber = uniqid();
        $isMemberOf = uniqid();
        $cert_subject_dn = uniqid();
        $oidc = uniqid();
        $token_id = uniqid();

        $postResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $postResponse->shouldReceive('getBody')->andReturn(
            '{"access_token":"mock_access_token"' .
            ',"token_type":"bearer"' .
            ',"refresh_token":"mock_refresh_token"}'
        );
        $postResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $postResponse->shouldReceive('getStatusCode')->andReturn(200);

        $userResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $userResponse->shouldReceive('getBody')->andReturn(
            '{"sub":"' . $id .
            '","name":"' . $name .
            '","given_name":"' . $given_name .
            '","family_name":"' . $family_name .
            '","eppn":"' . $eppn .
            '","eptid":"' . $eptid .
            '","email":"' . $email .
            '","idp":"' . $idp .
            '","idp_name":"' . $idpname .
            '","ou":"' .  $ou .
            '","affiliation":"' . $affiliation .
            '","acr":"' . $acr .
            '","subject_id":"' . $subject_id .
            '","pairwise_id":"' .  $pairwise_id .
            '","voPersonExternalId":"' . $voPersonExternalId .
            '","uid":"' . $uid .
            '","uidNumber":"' . $uidNumber .
            '","isMemberOf":"' . $isMemberOf .
            '","cert_subject_dn":"' .  $cert_subject_dn .
            '","oidc":"' . $oidc .
            '","token_id":"' . $token_id . '"}'
        );
        $userResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $userResponse->shouldReceive('getStatusCode')->andReturn(200);

        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')
            ->times(2)
            ->andReturn($postResponse, $userResponse);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
        $user = $this->provider->getResourceOwner($token);

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($id, $user->getSub());
        $this->assertEquals($id, $user->toArray()['sub']);
        $this->assertEquals($name, $user->getName());
        $this->assertEquals($name, $user->toArray()['name']);
        $this->assertEquals($given_name, $user->getGivenName());
        $this->assertEquals($given_name, $user->getFirstName());
        $this->assertEquals($given_name, $user->toArray()['given_name']);
        $this->assertEquals($family_name, $user->getFamilyName());
        $this->assertEquals($family_name, $user->getLastName());
        $this->assertEquals($family_name, $user->toArray()['family_name']);
        $this->assertEquals($eppn, $user->getEPPN());
        $this->assertEquals($eppn, $user->toArray()['eppn']);
        $this->assertEquals($eptid, $user->getEPTID());
        $this->assertEquals($eptid, $user->toArray()['eptid']);
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($email, $user->toArray()['email']);
        $this->assertEquals($idp, $user->getIdP());
        $this->assertEquals($idp, $user->toArray()['idp']);
        $this->assertEquals($idpname, $user->getIdPName());
        $this->assertEquals($idpname, $user->toArray()['idp_name']);
        $this->assertEquals($ou, $user->getOU());
        $this->assertEquals($ou, $user->toArray()['ou']);
        $this->assertEquals($affiliation, $user->getAffiliation());
        $this->assertEquals($affiliation, $user->toArray()['affiliation']);
        $this->assertEquals($acr, $user->getAcr());
        $this->assertEquals($acr, $user->toArray()['acr']);
        $this->assertEquals($subject_id, $user->getSubjectId());
        $this->assertEquals($subject_id, $user->toArray()['subject_id']);
        $this->assertEquals($pairwise_id, $user->getPairwiseId());
        $this->assertEquals($pairwise_id, $user->toArray()['pairwise_id']);
        $this->assertEquals($voPersonExternalId, $user->getVoPersonExternalId());
        $this->assertEquals($voPersonExternalId, $user->toArray()['voPersonExternalID']);
        $this->assertEquals($uid, $user->getUID());
        $this->assertEquals($uid, $user->toArray()['uid']);
        $this->assertEquals($uidNumber, $user->getUIDNumber());
        $this->assertEquals($uidNumber, $user->toArray()['uidNumber']);
        $this->assertEquals($isMemberOf, $user->getIsMemberOf());
        $this->assertEquals($isMemberOf, $user->toArray()['isMemberOf']);
        $this->assertEquals($cert_subject_dn, $user->getCertSubjectDN());
        $this->assertEquals($cert_subject_dn, $user->toArray()['cert_subject_dn']);
        $this->assertEquals($oidc, $user->getOIDC());
        $this->assertEquals($oidc, $user->toArray()['oidc']);
        $this->assertEquals($token_id, $user->getTokenId());
        $this->assertEquals($token_id, $user->toArray()['token_id']);
    }

    /**
     * @expectedException League\OAuth2\Client\Provider\Exception\IdentityProviderException
     **/
    public function testExceptionThrownWhenErrorObjectReceived()
    {
        $status = rand(401, 599);
        $postResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $postResponse->shouldReceive('getBody')->andReturn(
            '{"error":"mock_error_name",' .
            '"error_description":"mock_error_message"}'
        );
        $postResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $postResponse->shouldReceive('getStatusCode')->andReturn($status);
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')
            ->times(1)
            ->andReturn($postResponse);
        $this->provider->setHttpClient($client);
        $token = $this->provider->getAccessToken(
            'authorization_code',
            ['code' => 'mock_authorization_code']
        );
    }

    /**
     * @expectedException League\OAuth2\Client\Provider\Exception\IdentityProviderException
     **/
    public function testExceptionThrownWnenHTTPErrorStatus()
    {
        $status = rand(401, 599);
        $reason = 'HTTP ERROR';
        $postResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $postResponse->shouldReceive('getBody')->andReturn('');
        $postResponse->shouldReceive('getHeader')->andReturn([]);
        $postResponse->shouldReceive('getStatusCode')->andReturn($status);
        $postResponse->shouldReceive('getReasonPhrase')->andReturn($reason);
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')
            ->times(1)
            ->andReturn($postResponse);
        $this->provider->setHttpClient($client);
        $token = $this->provider->getAccessToken(
            'authorization_code',
            ['code' => 'mock_authorization_code']
        );
    }
}
