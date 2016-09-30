<?php

namespace M6Web\Bundle\AwsBundle\Aws\S3;

use Aws\S3\S3Client;

/**
 * Bucket
 */
class Bucket
{
    /**
     * @var S3Client
     */
    private $client;

    /**
     * @var string
     */
    private $name;

    /**
     * __construct
     *
     * @param S3Client $client Aws S3 Client
     * @param string   $name   Bucket name
     */
    public function __construct(S3Client $client, $name)
    {
        $this->client = $client;
        $this->name   = $name;
    }

    /**
     * isValidName
     *
     * @return boolean
     */
    public function isValidName()
    {
        return S3Client::isValidBucketName($this->name);
    }

    /**
     * encodeKey
     *
     * @param string $key
     *
     * @return string
     */
    public static function encodeKey($key)
    {
        return S3Client::encodeKey($key);
    }

    /**
     * explodeKey
     *
     * @param string $key
     *
     * @return array
     */
    public static function explodeKey($key)
    {
        return S3Client::explodeKey($key);
    }

    /**
     * exist
     *
     * @param boolean $accept403
     * @param array   $options
     *
     * @return boolean
     */
    public function exist($accept403 = true, array $options = array())
    {
        return $this->client->doesBucketExist($this->name, $accept403, $options);
    }

    /**
     * waitUntilExist
     *
     * @param array $policy
     *
     * @return boolean
     */
    public function waitUntilExist(array $policy = array())
    {
        $params = [
            'Bucket' => $this->name,
            'Policy' => $policy,
        ];

        return $this->client->waitUntilBucketExists($params);
    }

    /**
     * waitUntilNotExist
     *
     * @param array $params
     *
     * @return boolean
     */
    public function waitUntilNotExist(array $params = array())
    {
        $params['Bucket'] = $this->name;

        return $this->client->waitUntilBucketNotExists($params);
    }

    /**
     * create
     *
     * @param array $params
     *
     * @return boolean
     */
    public function create(array $params = array())
    {
        if ($this->exist()) {
            return true;
        }

        $params['Bucket'] = $this->name;

        return $this->client->createBucket($params);
    }

    /**
     * clear
     *
     * @return integer
     */
    public function clear()
    {
        return $this->client->clearBucket($this->name);
    }

    /**
     * head
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function head()
    {
        return $this->client->headBucket(['Bucket' => $this->name]);
    }

    /**
     * delete
     *
     * @param boolean $wait
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function delete($wait = false)
    {
        $response = $this->client->deleteBucket(['Bucket' => $this->name]);

        if ($wait) {
            $this->client->waitUntilBucketNotExists(['Bucket' => $this->name]);
        }

        return $response;
    }

    /**
     * getAcl
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function getAcl()
    {
        return $this->client->getBucketAcl(['Bucket' => $this->name]);
    }

    /**
     * putAcl
     *
     * @param array $params
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function putAcl(array $params = array())
    {
        $params['Bucket'] = $this->name;

        return $this->client->putBucketAcl($params);
    }

    /**
     * getCors
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function getCors()
    {
        return $this->client->getBucketCors(['Bucket' => $this->name]);
    }

    /**
     * putCors
     *
     * @param array $corsRules
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function putCors(array $corsRules = array())
    {
        $params = [
            'Bucket'    => $this->name,
            'CORSRules' => $corsRules,
        ];

        return $this->client->putBucketCors($params);
    }

    /**
     * deleteCors
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function deleteCors()
    {
        return $this->client->deleteBucketCors(['Bucket' => $this->name]);
    }

    /**
     * getLifecycle
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function getLifecycle()
    {
        return $this->client->getBucketLifecycle(['Bucket' => $this->name]);
    }

    /**
     * putLifecycle
     *
     * @param array $rules
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function putLifecycle(array $rules = array())
    {
        $params = [
            'Bucket' => $this->name,
            'Rules'  => $rules,
        ];

        return $this->client->putBucketLifecycle($params);
    }

    /**
     * deleteLifecycle
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function deleteLifecycle()
    {
        return $this->client->deleteBucketLifecycle(['Bucket' => $this->name]);
    }

    /**
     * getLogging
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function getLogging()
    {
        return $this->client->getBucketLogging(['Bucket' => $this->name]);
    }

    /**
     * putLogging
     *
     * @param array $logging LoggingEnabled
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function putLogging(array $logging = array())
    {
        $params = [
            'Bucket'         => $this->name,
            'LoggingEnabled' => $logging,
        ];

        return $this->client->putBucketLogging($params);
    }

    /**
     * getNotification
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function getNotification()
    {
        return $this->client->getBucketNotification(['Bucket' => $this->name]);
    }

    /**
     * putNotification
     *
     * @param array $config Topic configuration
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function putNotification(array $config = array())
    {
        $params = [
            'Bucket'             => $this->name,
            'TopicConfiguration' => $config,
        ];

        return $this->client->putBucketNotification($params);
    }


    /**
     * getWebsite
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function getWebsite()
    {
        return $this->client->getBucketWebsite(['Bucket' => $this->name]);
    }

    /**
     * putWebsite
     *
     * @param array $params
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function putWebsite(array $params = array())
    {
        $params['Bucket'] = $this->name;

        return $this->client->putBucketWebsite($params);
    }

    /**
     * deleteWebsite
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function deleteWebsite()
    {
        return $this->client->deleteBucketWebsite(['Bucket' => $this->name]);
    }

    /**
     * getPolicy
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function getPolicy()
    {
        return $this->client->getBucketPolicy(['Bucket' => $this->name]);
    }

    /**
     * putPolicy
     *
     * @param mixed $policy mixed type: string|resource|\Guzzle\Http\EntityBodyInterface
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function putPolicy($policy)
    {
        $params = [
            'Bucket' => $this->name,
            'Policy' => $policy,
        ];

        return $this->client->putBucketPolicy($params);
    }

    /**
     * deletePolicy
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function deletePolicy()
    {
        return $this->client->deleteBucketPolicy(['Bucket' => $this->name]);
    }

    /**
     * policyExist
     * @param array $options
     *
     * @return boolean
     */
    public function policyExist(array $options = array())
    {
        return $this->client->doesBucketPolicyExist($this->name, $options);
    }

    /**
     * getTagging
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function getTagging()
    {
        return $this->client->getBucketTagging(['Bucket' => $this->name]);
    }

    /**
     * putTagging
     *
     * @param array $tagSet TagSet
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function putTagging(array $tagSet)
    {
        $params = [
            'Bucket' => $this->name,
            'TagSet' => $tagSet,
        ];

        return $this->client->putBucketTagging($params);
    }

    /**
     * deleteTagging
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function deleteTagging()
    {
        return $this->client->deleteBucketTagging(['Bucket' => $this->name]);
    }

    /**
     * getRequestPayment
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function getRequestPayment()
    {
        return $this->client->getBucketRequestPayment(['Bucket' => $this->name]);
    }

    /**
     * putRequestPayment
     *
     * @param string $payer
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function putRequestPayment($payer)
    {
        $params = [
            'Bucket' => $this->name,
            'Payer'  => $payer,
        ];

        return $this->client->putBucketRequestPayment($params);
    }

    /**
     * getVersioning
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function getVersioning()
    {
        return $this->client->getBucketVersioning(['Bucket' => $this->name]);
    }

    /**
     * putVersioning
     *
     * @param string $mfa
     * @param string $delete
     * @param string $status
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function putVersioning($mfa = null, $delete = null, $status = null)
    {
        $params = ['Bucket' => $this->name];

        if (!is_null($mfa)) {
            $params['MFA'] = $mfa;
        }

        if (!is_null($delete)) {
            $params['MFADelete'] = $delete;
        }

        if (!is_null($status)) {
            $params['Status'] = $status;
        }

        return $this->client->putBucketVersioning($params);
    }


    /**
     * headObject
     *
     * @param string $key
     * @param array  $params
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function headObject($key, array $params = array())
    {
        $params['Key']    = $key;
        $params['Bucket'] = $this->name;

        return $this->client->headObject($params);
    }

    /**
     * getObject
     *
     * @param string $key
     * @param array  $params
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function getObject($key, array $params = array())
    {
        $params['Key']    = $key;
        $params['Bucket'] = $this->name;

        return $this->client->getObject($params);
    }

    /**
     * getObjectTorrent
     *
     * @param string $key
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function getObjectTorrent($key)
    {
        $params = [
            'Bucket' => $this->name,
            'Key'    => $key,
        ];

        return $this->client->getObjectTorrent($params);
    }

    /**
     * getObjectAcl
     *
     * @param string $key
     * @param string $versionId
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function getObjectAcl($key, $versionId = '')
    {
        $params = [
            'Bucket' => $this->name,
            'Key'    => $key,
        ];

        if ($versionId) {
            $params['VersionId'] = $versionId;
        }

        return $this->client->getObjectAcl($params);
    }

    /**
     * putObject
     *
     * @param array $params
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function putObject(array $params = array())
    {
        $params['Bucket'] = $this->name;

        return $this->client->putObject($params);
    }

    /**
     * putObjectAcl
     *
     * @param array $params
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function putObjectAcl(array $params = array())
    {
        $params['Bucket'] = $this->name;

        return $this->client->putObjectAcl($params);
    }

    /**
     * restoreObject
     *
     * @param string  $key
     * @param integer $days
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function restoreObject($key, $days)
    {
        $params = [
            'Bucket' => $this->name,
            'Key'    => $key,
            'Days'   => $days,
        ];

        return $this->client->restoreObject($params);
    }

    /**
     * objectExist
     *
     * @param string $key
     * @param array  $options
     *
     * @return boolean
     */
    public function objectExist($key, array $options = array())
    {
        return $this->client->doesObjectExist($this->name, $key, $options);
    }

    /**
     * getObjectUrl
     *
     * @param string $key
     * @param mixed  $expires
     * @param array  $params
     *
     * @return string
     */
    public function getObjectUrl($key, $expires = null, array $params = array())
    {
        return $this->client->getObjectUrl($this->name, $key, $expires, $params);
    }

    /**
     * copyObject
     *
     * @param array $params
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function copyObject(array $params = array())
    {
        $params['Bucket'] = $this->name;

        return $this->client->copyObject($params);
    }

    /**
     * listObjects
     *
     * @param array $params
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function listObjects(array $params = array())
    {
        $params['Bucket'] = $this->name;

        return $this->client->listObjects($params);
    }

    /**
     * listObjectVersion
     *
     * @param array $params
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function listObjectVersion(array $params = array())
    {
        $params['Bucket'] = $this->name;

        return $this->client->listObjectVersions($params);
    }

    /**
     * deleteObject
     *
     * @param string $key
     * @param array  $params
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function deleteObject($key, array $params = array())
    {
        $params['Key']    = $key;
        $params['Bucket'] = $this->name;

        return $this->client->deleteObject($params);
    }

    /**
     * deleteMatchingObjects
     *
     * @param string $prefix
     * @param string $regex
     * @param array  $options
     *
     * @return integer
     */
    public function deleteMatchingObjects($prefix = '', $regex = '', array $options = array())
    {
        return $this->client->deleteMatchingObjects($this->name, $prefix, $regex, $options);
    }

    /**
     * listParts
     *
     * @param string  $key
     * @param string  $uploadId
     * @param integer $maxParts
     * @param integer $maxNumberMarker
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function listParts($key, $uploadId, $maxParts = null, $maxNumberMarker = null)
    {
        $params = [
            'Bucket'   => $this->name,
            'Key'      => $key,
            'UploadId' => $uploadId,
        ];

        if (!is_null($maxParts)) {
            $params['MaxParts'] = $maxParts;
        }

        if (!is_null($maxNumberMarker)) {
            $params['MaxNumberMarker'] = $maxNumberMarker;
        }

        return $this->client->listParts($params);
    }

    /**
     * uploadPart
     *
     * @param string  $key
     * @param string  $uploadId
     * @param mixed   $body
     * @param integer $partNumber
     * @param string  $contentLength
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function uploadPart($key, $uploadId, $body, $partNumber, $contentLength = null)
    {
        $params = [
            'Bucket'     => $this->name,
            'Key'        => $key,
            'UploadId'   => $uploadId,
            'Body'       => $body,
            'PartNumber' => $partNumber,
        ];

        if (!is_null($contentLength)) {
            $params['contentLength'] = $contentLength;
        }

        return $this->client->uploadPart($params);
    }

    /**
     * uploadPartCopy
     *
     * @param array $params
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function uploadPartCopy(array $params)
    {
        $params['Bucket'] = $this->name;

        return $this->client->uploadPartCopy($params);
    }

    /**
     * upload
     *
     * @param string $key
     * @param mixed  $body
     * @param string $acl
     * @param array  $options
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function upload($key, $body, $acl = 'private', array $options = array())
    {
        return $this->client->upload($this->name, $key, $body, $acl, $options);
    }

    /**
     * createMultipartUpload
     *
     * @param array $params
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function createMultipartUpload(array $params = array())
    {
        $params['Bucket'] = $this->name;

        return $this->client->createMultipartUpload($params);
    }

    /**
     * completeMultipartUpload
     *
     * @param array $params
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function completeMultipartUpload(array $params = array())
    {
        $params['Bucket'] = $this->name;

        return $this->client->completeMultipartUpload($params);
    }

    /**
     * listMultipartUpload
     *
     * @param array $params
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function listMultipartUpload(array $params = array())
    {
        $params['Bucket'] = $this->name;

        return $this->client->listMultipartUploads($params);
    }

    /**
     * abortMultipartUpload
     *
     * @param string $key
     * @param string $uploadId
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function abortMultipartUpload($key, $uploadId)
    {
        return $this->client->abortMultipartUpload([
            'Bucket'   => $this->name,
            'Key'      => $key,
            'UploadId' => $uploadId,
        ]);
    }

    /**
     * uploadDirectory
     *
     * @param string $directory
     * @param string $keyPrefix
     * @param array  $options
     *
     * @return mixed
     */
    public function uploadDirectory($directory, $keyPrefix = null, array $options = array())
    {
        return $this->client->uploadDirectory($directory, $this->name, $keyPrefix, $options);
    }

    /**
     * download
     *
     * @param string $directory
     * @param string $keyPrefix
     * @param array  $options
     */
    public function download($directory, $keyPrefix = null, array $options = array())
    {
        $this->client->downloadBucket($directory, $this->name, $keyPrefix, $options);
    }

    /**
     * getClient
     *
     * @param S3Client $client Aws S3 Client
     *
     * @return Bucket
     */
    public function setClient(S3Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * getClient
     *
     * @return S3Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
