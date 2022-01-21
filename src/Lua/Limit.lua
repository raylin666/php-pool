local key = KEYS[1] -- 要进行限流的Key，可以是 uri
local consume_permits = tonumber(ARGV[1]) -- 请求消耗的令牌数，每个请求消耗一个
local curr_time = tonumber(ARGV[2]) -- 当前时间
local input_bucket_cap = tonumber(ARGV[3])
local input_rate = tonumber(ARGV[4])
local input_period = tonumber(ARGV[5])
-- curr_permits 当前桶内剩余令牌数量
-- bucket_cap 桶容量
-- rate 令牌生产速率 个/秒
-- period 限流的时间周期，单位为：秒。
local limiter_info = redis.pcall("HMGET", key, "last_time", "curr_permits", "bucket_cap", "rate", "period")
if not limiter_info[3] then
    redis.pcall("HMSET", key, "curr_permits", tonumber(ARGV[3]), "last_time", curr_time, "bucket_cap", input_bucket_cap, "rate", input_rate, "period", input_period)
end

if limiter_info[4] ~= input_rate then
    redis.pcall("HMSET", key, "rate", input_rate)
end

local last_time = tonumber(limiter_info[1]) or 0
local curr_permits = tonumber(limiter_info[2]) or 0
local bucket_cap = tonumber(limiter_info[3]) or tonumber(ARGV[3])
local rate = tonumber(limiter_info[4]) or 0
local period = tonumber(limiter_info[5]) or 0

local total_permits = bucket_cap
local is_update_time = true
if last_time > 0 then
    local new_permits = math.floor((curr_time-last_time)/1000 * rate)
    if new_permits <= 0 then
        new_permits = 0
        is_update_time = false
    end

    total_permits = new_permits + curr_permits
    if total_permits > bucket_cap then
        total_permits = bucket_cap
    end
else
    last_time = curr_time + period * 1000
end

local res = 1
if total_permits >= consume_permits then
    total_permits = total_permits - consume_permits
else
    res = 0
end

if is_update_time then
    redis.pcall("HMSET", key, "curr_permits", total_permits, "last_time", curr_time)
else
    redis.pcall("HSET", key, "curr_permits", total_permits)
end

return res
