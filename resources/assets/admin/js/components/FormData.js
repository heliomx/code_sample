export default function ($http, url){

    let form = new FormData();
    const config = {
        headers: {
          'content-type': 'multipart/form-data'
        }
      };

    function post()
    {
        return $http.post(url, form, config)
    }

    function patch()
    {
        return $http.patch(url, form, config)
    }

    function reset()
    {
        form = new FormData();
    }

    return {
        inputs: form,
        reset: reset,
        post: post,
        patch: patch
    }
}