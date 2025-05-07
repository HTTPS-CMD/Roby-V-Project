export async function decryptAES(encryptedBase64: string) {
    const rawData = Uint8Array.from(atob(encryptedBase64), (c) =>
        c.charCodeAt(0)
    );

    const iv = rawData.slice(0, 16);
    const data = rawData.slice(16);

    const encoder = new TextEncoder();
    const cryptoKey = await window.crypto.subtle.importKey(
        "raw",
        encoder.encode(import.meta.env.VITE_V2RAY_SECRET_KEY),
        { name: "AES-CBC" },
        false,
        ["decrypt"]
    );

    const decrypted = await window.crypto.subtle.decrypt(
        {
            name: "AES-CBC",
            iv,
        },
        cryptoKey,
        data
    );

    return new Promise<string>(async (resolve, reject) => {
        let text = await new TextDecoder().decode(decrypted);
        if (text.length) resolve(JSON.parse(text));
        else reject({ err: "no code detected" });
    });
}
