# AWS-BUNDLE [![Build Status](https://travis-ci.org/M6Web/AwsBundle.svg?branch=master)](https://travis-ci.org/M6Web/AwsBundle)

#### Aws client as a Symfony Service



### configure your AWS user credentials and services

**Reference guide of AWS configuration services**.
 See [AWS Configuration reference](http://docs.aws.amazon.com/aws-sdk-php/guide/latest/configuration.html)

- `m6_web_aws`:
    - `credentials`: # List of AWS user credentials
        - `default`: Name of AWS user
            - `key`: "azerty" [optional] Your AWS user's access key ID. See [AWS access keys](http://aws.amazon.com/fr/developers/access-keys/)
            - `secret`: "1337" [optional] Your AWS user's secret access key. See [AWS access keys](http://aws.amazon.com/fr/developers/access-keys/)
            - `region`: "us-west-2" [optional] Region name (e.g., 'us-east-1', 'us-west-1', 'us-west-2', 'eu-west-1', etc.)
            - `scheme`: [optional] URI Scheme of the base URL (e.g.. 'https', 'http') used when base_url is not supplied.
            - `base_url`: [optional] Allows you to specify a custom endpoint instead of having the SDK build one automatically from the region and scheme.
            - `signature`: [optional]
            - `signature_service`: [optional] Alias of signature.service. The signature service scope for Signature V4. See [Setting a custom endpoint](http://docs.aws.amazon.com/aws-sdk-php/guide/latest/configuration.html#custom-endpoint)
            - `signature_region`: [optional] Alias of signature.region. The signature region scope for Signature V4. See [Setting a custom endpoint](http://docs.aws.amazon.com/aws-sdk-php/guide/latest/configuration.html#custom-endpoint)
            - `curl_options`: [optional] Alias of curl.options
            - `request_options`: [optional] Alias of request.options
            - `command_params`: [optional] Alias of command.params
        - `ops`: # Another AWS user
            - `key`: ...
            - `secret`:
            - `region`:

    - `clients`:
        - `6cloud_cdn`:
            - `credential`: "default" [optional] AWS user name
            - `service`: "S3" [required] AWS service Alias (see below)
            - `region`: "us-west-1" [optional] Override region name.

    - `s3`:
        - `buckets`:
            - `dev`: Name of the bucket (use to define service name)
                - `name`: "s3-bucket-name" Real name of the bucket
                - `client`: "6cloud_cdn" Client name defined above
    - `sqs`:
        - `dev`: Name of the sqs config (use to define service name)
            - `client`: "sqs_client" Client name defined above

    - `sts`:
        - `dev`: Name of the sts config (use to define service name)
            - `client`: "sts_client" Client name defined above

    - `dynamodb`:
        - `dev`: Name of the client
            - `client`: "6cloud_cdn" Client name defined above


### Aliases for AWS Services :

**(case sensitive)**

 - AutoScaling
 - CloudFormation
 - CloudFront
 - cloudfront (version : 2012-05-05)
 - CloudSearch
 - cloudsearch (version : 2011-02-01)
 - CloudTrail
 - CloudWatch
 - DataPipeline
 - DirectConnect
 - DynamoDb
 - dynamodb (version: 2011-12-05)
 - Ec2
 - ElastiCache
 - ElasticBeanstalk
 - ElasticLoadBalancing
 - ElasticTranscoder
 - Emr
 - Glacier
 - Kinesis
 - Iam
 - ImportExport
 - OpsWorks
 - Rds
 - Redshift
 - Route53
 - S3
 - SimpleDb
 - Ses
 - Sns
 - Sqs
 - StorageGateway
 - Sts
 - Support
 - Swf

# SQS Example

```
    $client = $this->getContainer()->get('m6web_aws.sqs.workers');
    $queue = $client->getQueue('queue_test');

    for ($i=0; $i<100; $i++) {
        echo $client->sendMessage($queue, "hello world $i") . "\n";
    }

    $i = 0;
    while($messages = $client->receiveMessage($queue, 10)) {
        foreach($messages as $message) {
            echo $message['Body'] . "... ";
            if ($client->deleteMessage($queue, $message['ReceiptHandle'])) {
                echo "OK\n";
                $i++;
            } else echo "ERROR\n";
        }
    }

    echo"\n===> READ : $i\n";
```

# STS Example

```
    $client = $this->getContainer()->get('m6web_aws.sts.m6');

    $sessionToken = $client->getSessionToken();
    $credentials  = $client->createCredentials($sessionToken);

    echo "Key : " . $credentials->getSecretKey() . "\n";
    echo "Token : " . $credentials->getSecurityToken() . "\n";
```

### DataCollector

DataCollector is enabled by defaut.

To disable :

```
m6_web_aws:
    disable_data_collector: true
```

# Unit tests :

```
    composer install
    ./bin/atoum
```
