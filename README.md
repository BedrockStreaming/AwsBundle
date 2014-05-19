# AWS-BUNDLE

#### Aws client as a Symfony Service



### configure your credentials accounts and services

**Reference guide of AWS configuration services**. 
 See [http://docs.aws.amazon.com/aws-sdk-php/guide/latest/configuration.html](AWS Configuration reference)

- `m6_web_aws`:
    - `credentials`: # List of credentials accounts
        - `default`: Name of account
            - `key`: "azerty" [required] Your AWS access key ID. See [http://aws.amazon.com/fr/developers/access-keys/](AWS access keys)
            - `secret`: "1337" [required] Your AWS secret access key. See [http://aws.amazon.com/fr/developers/access-keys/](AWS access keys)
            - `region`: "us-west-2" [required] Region name (e.g., 'us-east-1', 'us-west-1', 'us-west-2', 'eu-west-1', etc.)
            - `scheme`: [optional] URI Scheme of the base URL (e.g.. 'https', 'http') used when base_url is not supplied.
            - `base_url`: [optional] Allows you to specify a custom endpoint instead of have the SDK build one automatically from the region and scheme.
            - `signature`: [optional] 
            - `signature_service`: [optional] Alias of signature.service. The signature service scope for Signature V4. See [http://docs.aws.amazon.com/aws-sdk-php/guide/latest/configuration.html#custom-endpoint](Setting a custom endpoint)
            - `signature_region`: [optional] Alias of signature.region. The signature region scope for Signature V4. See [http://docs.aws.amazon.com/aws-sdk-php/guide/latest/configuration.html#custom-endpoint](Setting a custom endpoint)
            - `curl_options`: [optional] Alias of curl.options
            - `request_options`: [optional] Alias of request.options
            - `command_params`: [optional] Alias of command.params
        - `ops`: # Another accounts
            - `key`: ...
            - `secret`:
            - `region`:

    - `clients`:
        - `6cloud_cdn`:
            - `credential`: "default" [required] Account name
            - `service`: "S3" [required] AWS service Alias (see below)
            - `region`: "us-west-1" [optional] Override region name.

    - `s3`:
        - `buckets`:
            - `dev`: Name of the bucket (use to define service name)
                - `name`: "s3-bucket-name" Real name of the bucket
                - `client`: "6cloud_cdn" Client name defined above
    - `dynamodb`:
        - `dev`: Name of the client
            - `client`: "6cloud_cdn" Client name defined above


### AWS Services Alias :

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

# Unit Test

```
    composer install
    ./vendor/bin/atoum -d src/M6Web/Bundle/AwsBundle/Tests
```