function gettype(item) {
    return Object.prototype
        .toString
        .call(item)
        .replaceAll('[object ', '')
        .replaceAll(']', '')
}

function typeIs(item, mustBe) {
    return gettype(item) === mustBe
}

function validMethod(method) {
    if (gettype(method) != "String") {
        return null;
    }

    method = String(method).toUpperCase().trim()

    return (
        ['GET', 'POST', 'PATCH', 'DELETE']
            .includes(
                method
            )
    ) ? method : null
}

async function callAjax(requestData) {
    if (!requestData || !(["Object", "DOMStringMap"].includes(gettype(requestData)))) {
        console.error('Invalid data', 'requestData:', ...requestData);
        return;
    }

    let url = requestData.ajaxUrl
    let method = requestData.ajaxMethod
    let headers = requestData.ajaxHeaders              // TODO
    let params = requestData.ajaxParams                // TODO
    let bodyJson = requestData.ajaxBodyJson            // TODO
    let bodyText = requestData.ajaxBodyText            // TODO
    let onSuccess = requestData.ajaxOnSuccess          // TODO
    let onError = requestData.ajaxOnError              // TODO
    let onEver = requestData.ajaxOnEver                // TODO
    let displayErrors = requestData.ajaxDisplayErrors  // TODO
    displayErrors = typeIs(displayErrors, 'String') && (displayErrors.trim().toUpperCase()) == 'TRUE'

    method = validMethod(method)
    onSuccess = gettype(onSuccess) == "Function" ? onSuccess : ((success) => console.log(success))
    onError = gettype(onError) == "Function" ? onError : ((error) => displayErrors && console.log(error))
    onEver = gettype(onEver) == "Function" ? onEver : ((ever) => console.log(ever))

    if (!url || !method || !method) {
        console.error('URL and method are required', 'requestData:', ...requestData);
        return;
    }

    await fetch(
        url,
        {
            method: method,
            headers: {
                "Content-Type": "application/json",
            },
        }
    )
        .then(response => {
            try {
                return response.json()
            } catch (error) {
                if (displayErrors) {
                    throw error
                }
            }
        })
        .then(data => {
            try {
                onSuccess(data)
            } catch (error) {
                if (displayErrors) {
                    throw error
                }
            }
        })
        .catch(data => {
            if (!displayErrors) {
                return
            }

            onEver(error)
        })
        .finally(error => {
            try {
                onEver(data)
            } catch (error) {
                if (displayErrors) {
                    throw error
                }
            }
        })
}
