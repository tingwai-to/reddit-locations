from __future__ import print_function
import get_data
import handle_s3
import handle_dynamodb
import handle_rds


def lambda_handler(event, context):
    print("Log stream name: ", context.log_stream_name)
    print("Log group name: ",  context.log_group_name)
    print(event['time'])

    subname = 'earthporn'

    submissions = get_data.reddit_data(subname)
    for post in submissions:
        try:
            print(post['id'])
            if handle_s3.file_exists(post):
                continue
            else:
                canUpload = get_data.save_image(post)

                if canUpload:
                    handle_s3.upload_image(post)
                    handle_rds.insert_metadata(post)
        except Exception as exc:
            print(exc)
            raise exc

    print(context.get_remaining_time_in_millis())
