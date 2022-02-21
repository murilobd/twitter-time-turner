export async function fetcher(endPoint, fetchOptions, params) {
    const LOCAL_STORAGE_ACCESS_TOKEN_KEY = params?.accessTokenKey || import.meta.env.VITE_ACCESS_TOKEN_KEY || "access-token";
    const BASE_URL = params?.baseUrl || import.meta.env.VITE_BACKEND_URL;
    const localStorageAccessToken = localStorage.getItem(LOCAL_STORAGE_ACCESS_TOKEN_KEY);

    if (fetchOptions?.body) {
        fetchOptions.body = JSON.stringify(fetchOptions.body);
    }

    const fetchRequest = await fetch(`${BASE_URL}${endPoint}`, {
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
}
