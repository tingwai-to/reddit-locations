from __future__ import print_function
import get_data
import handle_s3
import handle_dynamodb

def lambda_handler(event, context):
    print("Log stream name: ", context.log_stream_name)
    print("Log group name: ",  context.log_group_name)
    print(event['time'])

    subname = 'earthporn'

    try:
        submissions = get_data.reddit_data(subname)
        for post in submissions:
            canUpload = get_data.save_image(post)

            if canUpload:
                handle_s3.upload_image(post)
                item = handle_dynamodb.dict_to_dynamodb_item(post)
                handle_dynamodb.upload_metadata(item)

        print(context.get_remaining_time_in_millis())
        return True
    except Exception as exc:
        print(exc)
        return False
