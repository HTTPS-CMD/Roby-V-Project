export function useConvertFileSize(bytes: number, decimals: number = 0) {
    if (bytes === 0) return "0 MB"; // از MB شروع می‌کنیم

    const k = 1000;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
    let i = Math.floor(Math.log(bytes) / Math.log(k));

    // اگر کمتر از MB بود، واحد را MB در نظر بگیر
    const minIndex = 2; // index مربوط به "MB"
    if (i < minIndex) i = minIndex;

    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i];
}

export const $d = (date: Date | string, type: "date" | "time" | "datetime") => {
    if (typeof date === "string") {
        date = new Date(date);
    }
    if (type === "date") {
        return date.toLocaleDateString("fa-IR");
    }
    if (type === "time") {
        return date.toLocaleTimeString("fa-IR");
    }
    if (type === "datetime") {
        return date.toLocaleString("fa-IR");
    }
};
