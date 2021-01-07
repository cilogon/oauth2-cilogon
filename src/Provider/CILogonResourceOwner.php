<?php

/**
 * This file is part of the cilogon/oauth2-cilogon library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    Terry Fleury <tfleury@cilogon.org>
 * @copyright 2020 University of Illinois
 * @license   https://opensource.org/licenses/NCSA NCSA
 * @link      https://github.com/cilogon/oauth2-cilogon GitHub
 */

namespace CILogon\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class CILogonResourceOwner implements ResourceOwnerInterface
{
    /**
     * Raw response
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array  $response
     */
    public function __construct(array $response = array())
    {
        $this->response = $response;
    }

    /**
     * Get resource owner id.
     *
     * @return string
     */
    public function getId()
    {
        return @$this->response['sub'] ?: null;
    }

    /**
     * An alias for getId().
     *
     * @return string
     */
    public function getSub()
    {
        return $this->getId();
    }

    /**
     * Get resource owner display name.
     *
     * @return string
     */
    public function getName()
    {
        return @$this->response['name'] ?: null;
    }

    /**
     * Get resource owner given (first) name.
     *
     * @return string
     */
    public function getGivenName()
    {
        return @$this->response['given_name'] ?: null;
    }

    /**
     * Get resource owner given (first) name.
     * Alias for getGivenName().
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->getGivenName();
    }

    /**
     * Get resource owner family (last) name.
     *
     * @return string
     */
    public function getFamilyName()
    {
        return @$this->response['family_name'] ?: null;
    }

    /**
     * Get resource owner family (last) name.
     * Alias for getFamilyName();
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->getFamilyName();
    }

    /**
     * Get resource owner eduPersonPrincipalName.
     *
     * @return string
     */
    public function getEPPN()
    {
        return @$this->response['eppn'] ?: null;
    }

    /**
     * Get resource owner eduPersonTargetedID.
     *
     * @return string
     */
    public function getEPTID()
    {
        return @$this->response['eptid'] ?: null;
    }

    /**
     * Get resource owner email address.
     *
     * @return string
     */
    public function getEmail()
    {
        return @$this->response['email'] ?: null;
    }

    /**
     * Get the Identity Provider entityId the resource owner used for
     * authentication.
     *
     * @return string
     */
    public function getIdP()
    {
        return @$this->response['idp'] ?: null;
    }

    /**
     * Get the Identity Provider display name the resource owner used
     * for authentication.
     *
     * @return string
     */
    public function getIdPName()
    {
        return @$this->response['idp_name'] ?: null;
    }

    /**
     * Get resource owner organizational unit.
     *
     * @return string
     */
    public function getOU()
    {
        return @$this->response['ou'] ?: null;
    }

    /**
     * Get resource owner (scoped) affiliation.
     *
     * @return string
     */
    public function getAffiliation()
    {
        return @$this->response['affiliation'] ?: null;
    }

    /**
     * Get the Authentication Context Class Reference (ACR) value
     * for the transaction. Typically used for MFA.
     *
     * @return string
     */
    public function getAcr()
    {
        return @$this->response['acr'] ?: null;
    }

    /**
     * Get the resource owner Subject Id.
     *
     * @return string
     */
    public function getSubjectId()
    {
        return @$this->response['subject_id'] ?: null;
    }

    /**
     * Get the resource owner Pairwise Id.
     *
     * @return string
     */
    public function getPairwiseId()
    {
        return @$this->response['pairwise_id'] ?: null;
    }

    /**
     * Get the resource ownder voPersonExternalId.
     * Part of the voPerson schema.
     * https://github.com/voperson/voperson
     *
     * @return string
     */
    public function getVoPersonExternalId()
    {
        return @$this->response['voPersonExternalID'] ?: null;
    }

    /**
     * Get the resource owner (Unix) UID name.
     *
     * @return string
     */
    public function getUID()
    {
        return @$this->response['uid'] ?: null;
    }

    /**
     * Get the resource owner (Unix) UID number.
     *
     * @return string
     */
    public function getUIDNumber()
    {
        return @$this->response['uidNumber'] ?: null;
    }

    /**
     * Get the resource owner group membership.
     *
     * @return string
     */
    public function getIsMemberOf()
    {
        return @$this->response['isMemberOf'] ?: null;
    }

    /**
     * Get the resource owner X509 certificate subject
     * Distinguished Name (DN)
     *
     * @return string
     */
    public function getCertSubjectDN()
    {
        return @$this->response['cert_subject_dn'] ?: null;
    }

    /**
     * Get the resource owner OpenID Connect sub as
     * returned by the OIDC Provider (i.e., Google,
     * GitHub, and ORCID).
     *
     * @return string
     */
    public function getOIDC()
    {
        return @$this->response['oidc'] ?: null;
    }

    /**
     * Get the access token ID for the transaction.
     *
     * @return string
     */
    public function getTokenId()
    {
        return @$this->response['token_id'] ?: null;
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
