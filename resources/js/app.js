const components = {
    loadingDots: `<span class="loading">
    <span style="background-color: #fff;"></span>
    <span style="background-color: #fff;"></span>
    <span style="background-color: #fff;"></span>
    </span>`,
    thinking:
        '<span class="animate-pulse text-gray-600 text-sm">Tinking...</span>',
    chat_user: `
    <div class="ml-16 flex justify-end">
        <di class="bg-gray-100 p-3 rounded-md">
            <p class="font-medium text-blue-500 text-right text-sm">Question</p>
            <hr class="my-2" />
            <p class="text-gray-800">{content}</p>
        </di>
    </div>`,
    chat_bot: `
    <div class="bg-gray-100 p-2 rounded-md mr-16">
        <p class="font-medium text-blue-500 text-sm">Answer</p>
        <hr class="my-2" />
        <p class="text-gray-800" id="{id}">{content}</p>
    </div>`,
};

function isUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (error) {
        return false;
    }
}

function getId(length = 6) {
    const characters =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let result = "";

    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        result += characters.charAt(randomIndex);
    }

    return result;
}

function handleSubmitIndexing(form) {
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const link = e.target.link.value;
        const token = e.target._token.value;
        const progress = document.getElementById("progress-text");
        const btn = document.getElementById("btn-submit-indexing");
        progress.style.paddingBottom = "8px";
        btn.innerHTML = components.loadingDots;

        if (!link) return;
        const body = { link };
        fetch("/embedding", {
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": token,
            },
            method: "POST",
            body: JSON.stringify(body),
        })
            .then(async (res) => {
                const reader = res.body.getReader();
                const decoder = new TextDecoder();

                let text = "";
                while (true) {
                    const { value, done } = await reader.read();
                    if (done) break;
                    text = decoder.decode(value, { stream: true });
                    progress.innerText = text;
                }

                if (isUrl(text)) {
                    window.location = text;
                } else {
                    progress.innerText = "";
                    progress.style.borderBottom = 0;
                }

                btn.innerHTML = `Submit`;
            })
            .catch((e) => {
                console.error(e);
            });
    });
}

function handleSubmitQuestion(form) {
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const question = e.target.question.value;
        const chat_id = e.target._chat_id.value;
        const token = e.target._token.value;
        const btn = document.getElementById("btn-submit-question");
        const messages = document.getElementById("messages");
        btn.innerHTML = components.loadingDots;
        e.target.question.value = "";

        messages.innerHTML += components.chat_user.replace(
            "{content}",
            question
        );

        const answerComponentId = getId();
        messages.innerHTML += components.chat_bot
            .replace("{content}", "")
            .replace("{id}", answerComponentId);

        const answerComponent = document.getElementById(answerComponentId);
        answerComponent.innerHTML = components.thinking;

        if (!question) return;
        const body = { question, chat_id };
        fetch("/chat", {
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": token,
            },
            method: "POST",
            body: JSON.stringify(body),
        })
            .then(async (res) => {
                answerComponent.innerHTML = "";
                const reader = res.body.getReader();
                const decoder = new TextDecoder();

                let text = "";
                while (true) {
                    const { value, done } = await reader.read();
                    if (done) break;
                    text = decoder.decode(value, { stream: true });
                    answerComponent.innerText += text;
                }

                btn.innerHTML = `Submit`;
            })
            .catch((e) => {
                console.error(e);
            });
    });
}

const formSubmitLink = document.getElementById("form-submit-link");
if (formSubmitLink) handleSubmitIndexing(formSubmitLink);

const formQuestion = document.getElementById("form-question");
if (formQuestion) handleSubmitQuestion(formQuestion);
