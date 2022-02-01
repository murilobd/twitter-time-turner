export function defaultFetch() {
    return fetch(...arguments);
}

export default {
    install: (app, options) => {
        const localStorageAccessTokenKey = options?.accessTokenKey || "access-token";

        function getCookie(name) {
            const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            if (match) return match[2];
        }

        function fetcher(url, fetchOptions) {
            const localStorageAccessToken = localStorage.getItem(localStorageAccessTokenKey);

            if (fetchOptions?.body) {
                fetchOptions.body = JSON.stringify(fetchOptions.body);
            }
            return defaultFetch(url, {
                credentials: "include",
                headers: {
                    'Access-Control-Allow-Credentials': true,
                    'Content-Type': 'application/json',
                    'Accept': "application/json",
                    // automatically add access token if present to header
                    ...(localStorageAccessToken && {'Authorization': `Bearer ${localStorageAccessToken}`}),
                    // add/overwrite any other header options
                    ...(fetchOptions?.headers || {}),
                },
                ...(fetchOptions || {})
            });
        }

        app.config.globalProperties.$http = async (url, fetchOptions) => {
            try {
                const fetchRequest = await fetcher(url, fetchOptions);
                let fetchData = null;
                if (fetchRequest.status !== 204) {
                    fetchData = await fetchRequest.text();
                    fetchData = JSON.parse(fetchData);
                }

                const payload = {
                    status: fetchRequest.status,
                    statusText: fetchRequest.statusText,
                    data: fetchData
                };

                if (!fetchRequest.ok) {
                    console.log("nok");
                    return Promise.reject(payload);
                }

                return Promise.resolve(payload);
            } catch (error) {
                throw error;
            }
        }
    }
}
